(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    var root = document.documentElement;
    var body = document.body;
    var layout = document.querySelector(".admin-layout");
    var toggle = document.querySelector("[data-admin-menu-toggle]");
    var closers = document.querySelectorAll("[data-admin-menu-close]");
    var sidebar = document.getElementById("admin-sidebar");
    var mq = window.matchMedia ? window.matchMedia("(max-width: 820px)") : null;

    /* ---------- Sidebar drawer ---------- */
    function setOpen(open) {
      if (!layout || !toggle) return;
      layout.classList.toggle("admin-menu-open", open);
      root.classList.toggle("admin-menu-lock", open);
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.setAttribute("aria-label", open ? "Close admin navigation" : "Open admin navigation");
    }

    if (toggle) {
      toggle.addEventListener("click", function () {
        setOpen(toggle.getAttribute("aria-expanded") !== "true");
      });
    }
    closers.forEach(function (el) { el.addEventListener("click", function () { setOpen(false); }); });

    if (sidebar) {
      sidebar.querySelectorAll("a").forEach(function (a) {
        a.addEventListener("click", function () { setOpen(false); });
      });
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") setOpen(false);
    });

    function onViewportChange() { if (mq && !mq.matches) setOpen(false); }
    if (mq) {
      if (mq.addEventListener) mq.addEventListener("change", onViewportChange);
      else if (mq.addListener) mq.addListener(onViewportChange);
    }
    window.addEventListener("resize", onViewportChange, { passive: true });

    /* ---------- Highlight current page in the sidebar ---------- */
    if (sidebar) {
      var path = window.location.pathname.replace(/\/+$/, "") || "/";
      var best = null, bestLen = -1;
      sidebar.querySelectorAll(".admin-navigation .admin-nav-link").forEach(function (a) {
        var p = new URL(a.href, window.location.origin).pathname.replace(/\/+$/, "") || "/";
        var isAdminRoot = /\/admin$/.test(p);
        var match = isAdminRoot ? path === p : (path === p || path.indexOf(p + "/") === 0);
        if (match && p.length > bestLen) { best = a; bestLen = p.length; }
      });
      if (best) { best.classList.add("is-active"); best.setAttribute("aria-current", "page"); }
    }

    /* ---------- Responsive tables: copy <th> text into each <td> ---------- */
    document.querySelectorAll(".admin-table").forEach(function (table) {
      var heads = Array.prototype.map.call(
        table.querySelectorAll("thead th"),
        function (th) { return th.textContent.replace(/\s+/g, " ").trim(); }
      );
      table.querySelectorAll("tbody tr").forEach(function (row) {
        Array.prototype.forEach.call(row.children, function (cell, i) {
          if (cell.tagName === "TD" && !cell.hasAttribute("data-label") && heads[i]) {
            cell.setAttribute("data-label", heads[i]);
          }
        });
      });
    });
  });
})();
