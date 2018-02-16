(function () {
    $(function () {
        var e, t, n, r;
        return new CalendarEvents($("#external-events")), t = new Date, e = t.getDate(), n = t.getMonth(), r = t.getFullYear(), $("#calendar").fullCalendar({
            header: {
                left: "prev,next",
                center: "title",
                right: "month,agendaWeek,agendaDay"
            },
            editable: !0,
            droppable: !0,
            drop: function (e, t) {
                var n, r;
                r = $(this).data("eventObject"), n = $.extend({}, r), n.start = e, n.allDay = t, $("#calendar").fullCalendar("renderEvent", n, !0);
                if ($("#drop-remove").is(":checked")) return $(this).remove()
            },
            events: [{
                title: "All Day Event",
                start: new Date(r, n, 1)
            }, {
                title: "Long Event",
                start: new Date(r, n, e - 5),
                end: new Date(r, n, e - 2)
            }, {
                id: 999,
                title: "Repeating Event",
                start: new Date(r, n, e - 3, 16, 0),
                allDay: !1
            }, {
                id: 999,
                title: "Repeating Event",
                start: new Date(r, n, e + 4, 16, 0),
                allDay: !1
            }, {
                title: "Meeting",
                start: new Date(r, n, e, 10, 30),
                allDay: !1
            }, {
                title: "Lunch",
                start: new Date(r, n, e, 12, 0),
                end: new Date(r, n, e, 14, 0),
                allDay: !1
            }, {
                title: "Birthday Party",
                start: new Date(r, n, e + 1, 19, 0),
                end: new Date(r, n, e + 1, 22, 30),
                allDay: !1
            }, {
                title: "Click for Google",
                start: new Date(r, n, 28),
                end: new Date(r, n, 29),
                url: "http://google.com/"
            }]
        })
    })
}).call(this);