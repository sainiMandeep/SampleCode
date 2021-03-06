(function () {
    this.Theme = function () {
        function e() {}
        return e.colors = {
            darkGreen: "#779148",
            red: "#C75D5D",
            green: "#96c877",
            blue: "#6e97aa",
            orange: "#ff9f01",
            gray: "#6B787F",
            lightBlue: "#D4E5DE"
        }, e
    }(), $(function () {
        var e;
        
        $.uniform.defaults.fileButtonHtml = "+", $(".sparkline").each(function () {
            var e, t, n, r;
            return n = $(this).attr("data-color") || "red", r = "18px", $(this).hasClass("big") && (t = "5px", e = "2px", r = "30px"), $(this).sparkline("html", {
                type: "bar",
                barColor: Theme.colors[n],
                height: r,
                barWidth: t,
                barSpacing: e,
                zeroAxis: !1
            });
        }), 
        $(".tip, [rel=tooltip]").tooltip({
            gravity: "n",
            fade: !0,
            html: !0
        }), $("[data-percent]").each(function () {
            return $(this).css({
                width: "" + $(this).attr("data-percent") + "%"
            });
        }),
         $(".datepicker").datepicker({
            todayBtn: !0
        }), 
        //  $(".tags").tagsInput({
        //     width: "100%"
        // }),
        //  $("form.validatable").validationEngine({
        //     promptPosition: "topLeft"
        // }), 
        // $(".chzn-select").select2(), 
        // $(".textarea-html5").wysihtml5({
        //     "font-styles": !0,
        //     emphasis: !0,
        //     lists: !0,
        //     html: !1,
        //     link: !0,
        //     image: !0,
        //     color: !1,
        //     stylesheets: !1
        // }),
        $.extend($.fn.dataTableExt.oStdClasses, {
            sWrapper: "dataTables_wrapper form-inline"
        }),
       /*  $(".dTable").dataTable({
             bJQueryUI: !1,
             bAutoWidth: !1,
             sPaginationType: "full_numbers",
             sDom: '<"table-header"fl>t<"table-footer"ip>'
         }),*/
         $(".dTable-small").dataTable({
            iDisplayLength: 5,
            bJQueryUI: !1,
            bAutoWidth: !1,
            sPaginationType: "full_numbers",
            sDom: '<"table-header"fl>t<"table-footer"ip>'
        }), 
        // $("select.uniform, input:file, .dataTables_length select").uniform(), $(".core-animate-bars .box-toolbar a").click(function (e) {
        //     return e.preventDefault(), $(this).closest(".core-animate-bars").find(".progress .tip").each(function () {
        //         var e, t;
        //         return t = Math.floor(Math.random() * 80) + 20, e = "" + t + "%", $(this).attr("title", e).attr("data-percent", t).attr("data-original-title", e).css({
        //             width: e
        //         })
        //     })
        // }), 
        // $(".normal-slider").slider(), $(".ranged-slider-ui.normal").slider({
        //     range: !0,
        //     min: 0,
        //     max: 300,
        //     values: [40, 250]
        // }), $(".ranged-slider-ui.only-min").slider({
        //     range: "min",
        //     min: 0,
        //     max: 300,
        //     value: 40
        // }), $(".ranged-slider-ui.only-max").slider({
        //     range: "max",
        //     min: 0,
        //     max: 300,
        //     value: 240
        // }), $(".ranged-slider-ui.step").slider({
        //     range: "min",
        //     min: 20,
        //     max: 120,
        //     value: 40,
        //     step: 20,
        //     slide: function (e, t) {
        //         return $(".upload-max-size").html("" + t.value + " MB");
        //     }
        // }), 
        $(".ranged-slider-ui.vertical-bars span").each(function () {
            var e;
            return e = parseInt($(this).text(), 10), $(this).empty().slider({
                value: e,
                range: "min",
                animate: !0,
                orientation: "vertical"
            });
        }), 
        // $(".iButton-icons").iButton({
        //     labelOn: "<i class='icon-ok'></i>",
        //     labelOff: "<i class='icon-remove'></i>",
        //     handleWidth: 30
        // }), $(".iButton-enabled").iButton({
        //     labelOn: "ENABLED",
        //     labelOff: "DISABLED",
        //     handleWidth: 30
        // }), $(".iButton").iButton(), $(".iButton-icons-tab").each(function () {
        //     if ($(this).is(":visible")) return $(this).iButton({
        //         labelOn: "<i class='icon-ok'></i>",
        //         labelOff: "<i class='icon-remove'></i>",
        //         handleWidth: 30
        //     });
        // }),
         $('[data-toggle="tab"]').on("shown", function (e) {
            var t;
            return t = $(e.target).attr("href"), $(t).find(".iButton-icons-tab").iButton({
                labelOn: "<i class='icon-ok'></i>",
                labelOff: "<i class='icon-remove'></i>",
                handleWidth: 30
            });
        }), 
        // $("#thumbs a").touchTouch(), $(".closable-chat-box textarea").click(function () {
        //     return $(this).closest(".closable-chat-box").addClass("open");
            // , $(this).wysihtml5({
            //     "font-styles": !0,
            //     emphasis: !0,
            //     lists: !0,
            //     html: !1,
            //     link: !0,
            //     image: !0,
            //     color: !1,
            //     stylesheets: !1
            // });
        // }), 
        e = [], $(".justgage").each(function () {
            var t, n, r;
            return r = $(this).attr("data-labels") || !0, t = $(this).attr("data-gauge-width-scale") || 1, n = $(this).attr("data-animation-type") || "linear", e.push(new JustGage({
                id: $(this).attr("id"),
                min: 0,
                max: 100,
                title: $(this).attr("data-title"),
                value: getRandomInt(1, 80),
                label: "",
                levelColorsGradient: !1,
                showMinMax: r,
                gaugeWidthScale: t,
                startAnimationTime: 1e3,
                startAnimationType: ">",
                refreshAnimationTime: 1e3,
                refreshAnimationType: n,
                levelColors: [Theme.colors.green, Theme.colors.orange, Theme.colors.red]
            }))
        }), setInterval(function () {
            return $(e).each(function () {
                return this.refresh(getRandomInt(0, 80));
            });
        }, 2500), 
        // $(".easy-pie-chart").each(function () {
        //     var e;
        //     return e = $(this), $(this).easyPieChart({
        //         lineWidth: 10,
        //         size: 150,
        //         lineCap: "square",
        //         barColor: Theme.colors[e.data("color")] || Theme.colors.red,
        //         scaleColor: Theme.colors.gray,
        //         animate: 1e3
        //     });
        // }), $(".easy-pie-chart-small").each(function () {
        //     var e;
        //     return e = $(this), $(this).easyPieChart({
        //         lineWidth: 4,
        //         size: 40,
        //         lineCap: "square",
        //         barColor: Theme.colors[e.attr("data-color")] || Theme.colors.red,
        //         animate: 1e3
        //     });
        // }), $(".easy-pie-chart-percent").easyPieChart({
        //     animate: 1e3,
        //     trackColor: "#444",
        //     scaleColor: "#444",
        //     lineCap: "square",
        //     lineWidth: 15,
        //     size: 150,
        //     barColor: function (e) {
        //         return "rgb(" + Math.round(200 * e / 100) + ", " + Math.round(200 * (1 - e / 100)) + ", 0)";
        //     }
        // }), setInterval(function () {
        //     return $(".easy-pie-chart, .easy-pie-chart-percent").each(function () {
        //         var e;
        //         return e = getRandomInt(0, 80), $(this).data("easyPieChart").update(e), $(this).find("span").text("" + e + "%");
        //     });
        // }, 2500),
        $('.pop').each(function() {
            var element = $(this);
            $(this).popover({
                trigger: 'hover',
                placement: 'top',
                html:true, 
                title:false,
                content: element.attr('pop')
            });
        })
    });
}).call(this);

$(document).ready(function() {
	if ($('#IE8').size() == 0) {
		$(".dTable").dataTable({
			bJQueryUI: !1,
			bAutoWidth: !1,
			sPaginationType: "full_numbers",
			sDom: '<"table-header"fl>t<"table-footer"ip>'
		});
		$("select.uniform, input:file, .dataTables_length select").uniform(), $(".core-animate-bars .box-toolbar a").click(function (e) {
			return e.preventDefault(), $(this).closest(".core-animate-bars").find(".progress .tip").each(function () {
				var e, t;
				return t = Math.floor(Math.random() * 80) + 20, e = "" + t + "%", $(this).attr("title", e).attr("data-percent", t).attr("data-original-title", e).css({
					width: e
				})
			})
		});
        $(".icheck").iCheck({
            checkboxClass: "icheckbox_flat-aero",
            radioClass: "iradio_flat-aero"
        });
	}
	else {
		$(".dTable").dataTable({
			bJQueryUI: !1,
			bAutoWidth: !1,
			sPaginationType: "full_numbers",
			sDom: '<"table-header"fl>t<"table-footer"ip>'
		});
		
		
        $('.datepicker-dropdown').hide().removeClass('dropdown-menu').addClass('dropdown-menu-ie');
	}
});

function datepickerie() {
    if ($('#IE8').size() > 0) {
        $('.datepicker-dropdown').hide().removeClass('dropdown-menu').addClass('dropdown-menu-ie');
    }
}