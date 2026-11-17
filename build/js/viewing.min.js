/* ============================================================
   QTAX Now Serving — viewing.js
   UI redesign: renderServingCards rewritten.
   All AJAX, DataTables, Comet, video, bell logic preserved.
   ============================================================ */

const t_time1 = document.getElementById("txt-time1"),
  t_date  = document.getElementById("txt-date"),
  w1to6   = document.getElementById("waiting1to6"),
  p1to6   = document.getElementById("pending1to6");

let x = document.getElementById("bell"),
  dt_serving        = null,
  _dt_serving       = false,
  dt_serving_count  = 0,
  _oras = null,
  _d    = null,
  dd    = new Date(),
  html, html1, i,
  weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
  _m      = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
  video_list   = [],
  video_index  = 0,
  video_player = document.getElementById("videoplayer");

/* ---- Utility: JSON fetch ---------------------------------- */
var getJSON = function (url) {
  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();
    xhr.open("get", url, true);
    xhr.responseType = "json";
    xhr.onload = function () {
      if (xhr.status === 200) { resolve(xhr.response); }
      else                    { reject(xhr.status);    }
    };
    xhr.send();
  });
};

/* ---- Video file list -------------------------------------- */
getJSON(base_url + "viewing/ajax_get_file").then(
  function (data) {
    if (data.status === "yes" || data.status === true) {
      video_list = data.content;
      video_player.setAttribute("src", video_list[video_index]);
      video_player.volume = 0.9;
      var promise = video_player.play();
      if (promise !== undefined) {
        promise.then(function(){}).catch(function(){});
      }
    }
  },
  function () {}
);

/* ---- Video: advance to next file when ended -------------- */
const onvideoended = () => {
  video_index = (video_index < video_list.length - 1) ? video_index + 1 : 0;
  video_player.setAttribute("src", video_list[video_index]);
  var promise = video_player.play();
  if (promise !== undefined) {
    promise.then(function(){}).catch(function(){});
  }
};

/* ---- Clock ----------------------------------------------- */
var myVar = setInterval(myTimer, 1000);
function myTimer() {
  var d = new Date();
  t_time1.innerHTML = d.toLocaleTimeString().replace(/:(\d{2}) (?=[AP]M)/, " ");
}

/* ---- Date display ---------------------------------------- */
t_date.innerHTML =
  dd.getUTCDate() + " " +
  _m[dd.getUTCMonth()] + " " +
  dd.getUTCFullYear();

/* ---- Service label helper --------------------------------- */
function ifpayment(p) {
  return (p === "YES") ? "PAYMENT ONLY" : "";
}

function iftype(p, type, status) {
  if (type === "REGULAR")      return "Assessment";
  if (type === "SPECIAL")      return "Assessment - PWDs, Senior, Pregnant";
  if (type === "REGULAR5-10")  return "Payment";
  if (type === "SPECIAL5-10")  return "Payment - PWDs, Senior, Pregnant";
  return "";
}

/* ============================================================
   RENDER SERVING CARDS
   Builds the new card layout inside #qtax-serving-grid.
   Only visual output changes — DataTables drives the data.
   ============================================================ */
function renderServingCards(records) {
  var grid = document.getElementById("qtax-serving-grid");
  if (!grid) return;

  /* Sanitise input */
  records = Array.isArray(records)
    ? records.filter(function (r) { return r && typeof r === "object"; })
    : [];

  var count = records.length;

  /* Column / row count ------------------------------------- */
  var cols, rows;
  if (count === 0) {
    cols = 1; rows = 1;
  } else if (count <= 4) {
    cols = 1; rows = count;
  } else {
    cols = 2; rows = Math.ceil(count / 2);
  }

  /* Apply CSS custom properties */
  grid.style.setProperty("--ns-cols", cols);
  grid.style.setProperty("--ns-rows", rows);

  /* Size class for typography scaling */
  var sizeClass = "ns-size-" + Math.min(count, 12);
  grid.className = "ns-grid " + (count > 0 ? sizeClass : "");

  /* Clear existing cards */
  while (grid.firstChild) { grid.removeChild(grid.firstChild); }

  /* Empty state */
  if (count === 0) {
    var empty = document.createElement("div");
    empty.className = "ns-empty";
    empty.textContent = "Waiting for next customer\u2026";
    grid.appendChild(empty);
    return;
  }

  /* Build cards */
  var fragment = document.createDocumentFragment();

  records.forEach(function (record) {
    var windowNum  = record.window  || "-";
    var ticketNum  = record.number  || "-";
    var serviceLabel = iftype(record.ifpayment, record.type, record.status) || "Service";

    /* Card shell */
    var card = document.createElement("div");
    card.className = "ns-card";

    /* ---- Left section: WINDOW label + number ----------- */
    var left = document.createElement("div");
    left.className = "ns-card-left";

    var wLabel = document.createElement("div");
    wLabel.className = "ns-card-window-label";
    wLabel.textContent = "WINDOW";

    var wNum = document.createElement("div");
    wNum.className = "ns-card-window-num";
    wNum.textContent = windowNum;

    left.appendChild(wLabel);
    left.appendChild(wNum);

    /* ---- Divider --------------------------------------- */
    var divider = document.createElement("div");
    divider.className = "ns-card-divider";

    /* ---- Right section: ticket + service badge --------- */
    var right = document.createElement("div");
    right.className = "ns-card-right";

    var ticket = document.createElement("div");
    ticket.className = "ns-card-ticket";
    ticket.textContent = ticketNum;

    var service = document.createElement("div");
    service.className = "ns-card-service";
    service.textContent = serviceLabel;

    right.appendChild(ticket);
    right.appendChild(service);

    card.appendChild(left);
    card.appendChild(divider);
    card.appendChild(right);

    fragment.appendChild(card);
  });

  grid.appendChild(fragment);
}

/* ============================================================
   DATATABLES INIT
   Hidden table feeds renderServingCards via drawCallback.
   All options (ajax url, columns, callbacks) are unchanged.
   ============================================================ */
dt_serving = $("#dt_serving").DataTable({
  ajax: {
    type: "GET",
    url: base_url + "_getviewing.txt",
    dataSrc: "serving",
  },
  columns: [
    {
      data: function (data) {
        return (
          '<div class="col-left">' + data.number +
          ' <span>' + iftype(data.ifpayment, data.type, data.status) + '</span></div>' +
          '<div class="col-right">' + data.window + '<span>window</span></div>'
        );
      },
    },
  ],
  scrollCollapse: true,
  info:     false,
  paging:   false,
  searching:false,
  ordering: false,
  drawCallback: function () {
    var records = this.api().rows().data().toArray();
    renderServingCards(records);
  },
  fnCreatedRow: function (nRow, data) {
    $(nRow).attr("data-id", data.id);
    $(nRow).addClass("payment-" + data.ifpayment);
  },
  initComplete: function () {
    _dt_serving = true;
  },
});

/* ============================================================
   COMET — viewing (long-poll for queue updates)
   ============================================================ */
Comet1 = Class.create();
Comet1.prototype = {
  timestamp: 0,
  url: base_url + "_viewing.php",
  noerror: true,
  initialize: function () {},
  connect: function () {
    this.ajax = new Ajax.Request(this.url, {
      method: "get",
      parameters: { timestamp: this.timestamp },
      onSuccess: function (transport) {
        var response = transport.responseText.evalJSON();
        this.comet1.timestamp = response["timestamp"];
        this.comet1.handleResponse(response);
        this.comet1.noerror = true;
      },
      onComplete: function () {
        if (!this.comet1.noerror) {
          setTimeout(function () { comet1.connect(); }, 5000);
        } else {
          this.comet1.connect();
        }
        this.comet1.noerror = false;
      },
    });
    this.ajax.comet1 = this;
  },
  disconnect: function () {},
  handleResponse: function () {
    if (_dt_serving) {
      dt_serving.ajax.reload(function () {
        if (dt_serving.data().count() > dt_serving_count) {
          var promise = x.play();
          if (promise !== undefined) {
            promise.then(function(){}).catch(function(){});
          }
        }
        dt_serving_count = dt_serving.data().count();
      });
    }
  },
  doRequest: function (request) {
    new Ajax.Request(this.url, { method: "get", parameters: { msg: request } });
  },
};
var comet1 = new Comet1();
comet1.connect();

/* ============================================================
   COMET — mic check (bell trigger)
   ============================================================ */
var Comet4 = Class.create();
Comet4.prototype = {
  timestamp: 0,
  url: base_url + "_mic_check.php",
  noerror: true,
  initialize: function () {},
  connect: function () {
    this.ajax = new Ajax.Request(this.url, {
      method: "get",
      parameters: { timestamp: this.timestamp },
      onSuccess: function (transport) {
        var response = transport.responseText.evalJSON();
        this.comet4.timestamp = response["timestamp"];
        this.comet4.handleResponse(response);
        this.comet4.noerror = true;
      },
      onComplete: function () {
        if (!this.comet4.noerror) {
          setTimeout(function () { comet4.connect(); }, 5000);
        } else {
          this.comet4.connect();
        }
        this.comet4.noerror = false;
      },
    });
    this.ajax.comet4 = this;
  },
  disconnect: function () {},
  handleResponse: function () {
    var promise = x.play();
    if (promise !== undefined) {
      promise.then(function(){}).catch(function(){});
    }
  },
  doRequest: function (request) {
    new Ajax.Request(this.url, { method: "get", parameters: { msg: request } });
  },
};
var comet4 = new Comet4();
comet4.connect();
