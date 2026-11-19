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

function priorityLabel(type) {
  return (type === "SPECIAL" || type === "SPECIAL5-10") ? "PWDs, Senior, Pregnant" : "";
}

/* ============================================================
   RENDER SERVING CARDS
   Builds the new card layout inside #qtax-serving-grid.
   Only visual output changes — DataTables drives the data.
   ============================================================ */
function renderWaitingCards(waiting) {
    const container = document.getElementById(
        "qtax-up-next-tickets"
    );

    if (!container) return;

    const section = container.closest(".ns-up-next");
    const hasWaitingTickets = Array.isArray(waiting) && waiting.length > 0;
    container.innerHTML = "";
    section.hidden = !hasWaitingTickets;

    if (!hasWaitingTickets) return;

    waiting.forEach(function(ticket) {
        const item = document.createElement("li");

        item.textContent = ticket.number;
        item.classList.toggle("ns-waiting-special", Boolean(priorityLabel(ticket.type)));

        container.appendChild(item);
    });
}

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
    var label = priorityLabel(record.type);

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

    right.appendChild(ticket);
    if (label) {
      var service = document.createElement("div");
      service.className = "ns-card-service";
      service.textContent = label;
      right.appendChild(service);
    }

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
    url: base_url + "viewing/ajax_get_viewing",
    cache: false,

  dataSrc: function(response) {
    // Update the waiting cards
    renderWaitingCards(response.waiting || []);

    // Keep the existing serving logic
    return response.serving || [];
  }
  },
  columns: [
    {
      data: function (data) {
        var label = priorityLabel(data.type);
        return (
          '<div class="col-left">' + data.number +
          (label ? ' <span>' + label + '</span>' : '') + '</div>' +
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
    // Tickets already on screen are the baseline for later queue updates.
    dt_serving_count = this.api().data().count();
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
        // Skip while an announcement is active: it plays the bell itself.
        if (dt_serving.data().count() > dt_serving_count && !announcer.isActive()) {
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

/* ============================================================
   ANNOUNCER — "Now serving" bell + text-to-speech
   Long-polls _announce_check.php. Items carry an increasing
   sequence number: they are spoken in order, one at a time,
   and anything at or below the last seen sequence is ignored.
   ============================================================ */
var announcer = {
  queue: [],
  busy: false,
  utterance: null, // keep a reference so Chrome does not drop onend

  isActive: function () {
    return this.busy || this.queue.length > 0;
  },

  enqueue: function (item) {
    this.queue.push(item);
    this.next();
  },

  next: function () {
    if (this.busy || this.queue.length === 0) return;
    this.busy = true;

    var self = this,
      item = this.queue.shift(),
      text =
        "Now serving ticket number " + item.number +
        ". Please proceed to Window " + item.window + ".";

    announcerPlayBell(function () {
      announcerSpeak(text, function () {
        self.busy = false;
        self.next();
      });
    });
  },
};

/* Play the existing bell, then call done once (on end, error or timeout).
   If the bell is already ringing, wait for it instead of restarting it. */
function announcerPlayBell(done) {
  var finished = false, timer;

  function finish() {
    if (finished) return;
    finished = true;
    clearTimeout(timer);
    x.removeEventListener("ended", finish);
    x.removeEventListener("error", finish);
    done();
  }

  x.addEventListener("ended", finish);
  x.addEventListener("error", finish);
  timer = setTimeout(finish, 8000);

  if (x.paused || x.ended) {
    try { x.currentTime = 0; } catch (e) {}
    var promise = x.play();
    if (promise !== undefined) {
      promise.catch(finish);
    }
  }
}

/* Speak text with the browser's speech synthesis. Always calls done once,
   even if speech is unsupported, blocked, errors out or never ends. */
function announcerSpeak(text, done) {
  var finished = false, timer;

  function finish() {
    if (finished) return;
    finished = true;
    clearTimeout(timer);
    announcer.utterance = null;
    done();
  }

  if (!("speechSynthesis" in window) || typeof SpeechSynthesisUtterance === "undefined") {
    finish();
    return;
  }

  try {
    var u = new SpeechSynthesisUtterance(text);
    u.lang = "en-US";
    u.rate = 1.3;
    u.pitch = 1.5;   // 0 to 2, default 1
    u.volume = 1
    var voices = window.speechSynthesis.getVoices();
    for (var v = 0; v < voices.length; v++) {
      if (/^en([-_]|$)/i.test(voices[v].lang)) { u.voice = voices[v]; break; }
    }

    u.onend = finish;
    u.onerror = function (e) {
      if (window.console) console.warn("Announcement speech failed:", e.error || e);
      finish();
    };

    announcer.utterance = u;
    timer = setTimeout(function () {
      window.speechSynthesis.cancel();
      finish();
    }, 15000);

    window.speechSynthesis.speak(u);
  } catch (e) {
    if (window.console) console.warn("Announcement speech failed:", e);
    finish();
  }
}

/* Load voices early (Chrome fills getVoices() asynchronously). */
if ("speechSynthesis" in window) {
  window.speechSynthesis.getVoices();
}

/* Long-poll for new announcements. announceSeq = -1 means "not yet
   synced": the first response only records the current sequence so a
   page reload does not replay earlier announcements. */
var announceSeq = -1;

function pollAnnouncements() {
  var ok = false;

  new Ajax.Request(base_url + "_announce_check.php", {
    method: "get",
    parameters: { since: announceSeq },
    onSuccess: function (transport) {
      var res = null;
      try { res = JSON.parse(transport.responseText); } catch (e) {}
      if (!res || typeof res.seq !== "number") return;
      ok = true;

      // First sync, or the server log was reset: start from the current point.
      if (announceSeq < 0 || res.seq < announceSeq) {
        announceSeq = res.seq;
        return;
      }

      (res.items || []).forEach(function (item) {
        var seq = parseInt(item.seq, 10);
        if (seq > announceSeq) {
          announceSeq = seq;
          announcer.enqueue(item);
        }
      });
      if (res.seq > announceSeq) announceSeq = res.seq;
    },
    onComplete: function () {
      setTimeout(pollAnnouncements, ok ? 0 : 5000);
    },
  });
}
pollAnnouncements();
