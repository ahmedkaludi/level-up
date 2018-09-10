/*!
 * jquery-drawer v3.2.2
 * Flexible drawer menu using jQuery, iScroll and CSS.
 * http://git.blivesta.com/drawer
 * License : MIT
 * Author : blivesta <design@blivesta.com> (http://blivesta.com/)
 */

! function(e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof exports ? module.exports = e(require("jquery")) : e(jQuery)
}(function(e) {
    "use strict";
    var t = void 0 !== document.ontouchstart,
        s = {
            init: function(t) {
                return t = e.extend({
                    iscroll: {
                        mouseWheel: !0,
                        preventDefault: !1
                    },
                    showOverlay: !0
                }, t), s.settings = {
                    state: !1,
                    events: {
                        opened: "drawer.opened",
                        closed: "drawer.closed"
                    },
                    dropdownEvents: {
                        opened: "shown.bs.dropdown",
                        closed: "hidden.bs.dropdown"
                    }
                }, s.settings.class = e.extend({
                    nav: "drawer-nav",
                    toggle: "drawer-toggle",
                    overlay: "drawer-overlay",
                    open: "drawer-open",
                    close: "drawer-close",
                    dropdown: "drawer-dropdown"
                }, t.class), this.each(function() {
                    var n = this,
                        r = e(this);
                    r.data("drawer") || (t = e.extend({}, t), r.data("drawer", {
                        options: t
                    }), s.refresh.call(n), t.showOverlay && s.addOverlay.call(n), e("." + s.settings.class.toggle).on("click.drawer", function() {
                        return s.toggle.call(n), n.iScroll.refresh()
                    }), e(window).on("resize.drawer", function() {
                        return s.close.call(n), n.iScroll.refresh()
                    }), e("." + s.settings.class.dropdown).on(s.settings.dropdownEvents.opened + " " + s.settings.dropdownEvents.closed, function() {
                        return n.iScroll.refresh()
                    }))
                })
            },
            refresh: function() {
                this.iScroll = new IScroll("." + s.settings.class.nav, e(this).data("drawer").options.iscroll)
            },
            addOverlay: function() {
                var t = e(this),
                    n = e("<div>").addClass(s.settings.class.overlay + " " + s.settings.class.toggle);
                return t.append(n)
            },
            toggle: function() {
                var e = this;
                return s.settings.state ? s.close.call(e) : s.open.call(e)
            },
            open: function() {
                var n = e(this);
                return t && n.on("touchmove.drawer", function(e) {
                    e.preventDefault()
                }), n.removeClass(s.settings.class.close).addClass(s.settings.class.open).drawerCallback(function() {
                    s.settings.state = !0, n.trigger(s.settings.events.opened)
                })
            },
            close: function() {
                var n = e(this);
                return t && n.off("touchmove.drawer"), n.removeClass(s.settings.class.open).addClass(s.settings.class.close).drawerCallback(function() {
                    s.settings.state = !1, n.trigger(s.settings.events.closed)
                })
            },
            destroy: function() {
                return this.each(function() {
                    var t = this,
                        n = e(this);
                    e("." + s.settings.class.toggle).off("click.drawer"), e(window).off("resize.drawer"), e("." + s.settings.class.dropdown).off(s.settings.dropdownEvents.opened + " " + s.settings.dropdownEvents.closed), t.iScroll.destroy(), n.removeData("drawer").find("." + s.settings.class.overlay).remove()
                })
            }
        };
    e.fn.drawerCallback = function(t) {
        var s = "transitionend webkitTransitionEnd";
        return this.each(function() {
            var n = e(this);
            n.on(s, function() {
                return n.off(s), t.call(this)
            })
        })
    }, e.fn.drawer = function(t) {
        return s[t] ? s[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? void e.error("Method " + t + " does not exist on jQuery.drawer") : s.init.apply(this, arguments)
    }
});/*
 * smart sticky header js 
*/
! function(a) {
    a.fn.jPinning = function(b) {
        var c = {
                offset: !1,
                onPin: function() {},
                onUnpin: function() {}
            },
            d = a.extend({}, c, b),
            e = {
                lastScrollTop: 0,
                document: a(document),
                window: a(window),
                status: "pinned"
            },
            f = {
                nav: "pinning-nav",
                pinned: "pinned",
                unpinned: "unpinned",
                top: "pinning-top"
            },
            g = {
                isUnpinned: function() {
                    return "unpinned" == e.status ? !0 : !1
                },
                isPinned: function() {
                    return "pinned" == e.status ? !0 : !1
                },
                prepare: function() {
                    e.target.addClass(f.nav), e.target.css("position", "fixed")
                },
                pin: function() {
                    g.isUnpinned() && (e.status = "pinned", e.target.removeClass(f.unpinned).addClass(f.pinned), d.onPin.call(e.target))
                },
                unpin: function() {
                    g.isPinned() && (e.status = "unpinned", e.target.removeClass(f.pinned).removeClass(f.top).addClass(f.unpinned), d.onUnpin.call(e.target))
                },
                calcOffset: function(a) {
                    return "auto" == d.offset && (d.offset = e.target.outerHeight()), d.offset ? a > d.offset ? !0 : !1 : !0
                },
                pinHandler: function() {
                    var a = e.window.scrollTop(),
                        b = e.document.height() - e.window.height();
                    if (0 > a && (a = 0), a >= b && (a = b, e.lastScrollTop = a - 1), 0 == a && e.target.addClass(f.top), a <= e.lastScrollTop) g.pin();
                    else {
                        var c = g.calcOffset(a);
                        c && g.unpin()
                    }
                    e.lastScrollTop = a
                }
            };
        return this.each(function() {
            e.target = a(this), g.prepare(), a(window).on("scroll", g.pinHandler)
        })
    }
}(jQuery);