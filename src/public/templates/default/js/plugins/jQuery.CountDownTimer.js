(function ($) {
    $.fn.extend({

        countdown: function (options) {

            var defaults = {
                daysSelector: 'em.days',
                hoursHSelector: 'div.hoursH',
                hoursLSelector: 'div.hoursL',
                minutesHSelector: 'div.minutesH',
                minutesLSelector: 'div.minutesL',
                secondsHSelector: 'div.secondsH',
                secondsLSelector: 'div.secondsL'
            }

            var options = $.extend(defaults, options);
            var _this = $(this);

            var tick = function () {
                var days = _this.find(options.daysSelector);
                var hours = _this.find(options.hoursHSelector).text() + _this.find(options.hoursLSelector).text();
                var minutes = _this.find(options.minutesHSelector).text() + _this.find(options.minutesLSelector).text();
                var seconds = _this.find(options.secondsHSelector).text() + _this.find(options.secondsLSelector).text();

                days = $(days).text();

                var currentSeconds = seconds;
                currentSeconds--;
                if (currentSeconds < 0) {
                    _this.find(options.secondsHSelector).text('5');
                    _this.find(options.secondsLSelector).text('9');
                    var currentMinutes = minutes;
                    currentMinutes--;
                    if (currentMinutes < 0) {
                        _this.find(options.minutesHSelector).text('5');
                        _this.find(options.minutesLSelector).text('9');
                        var currentHours = (hours);
                        currentHours--;
                        if (currentHours < 0) {
                            _this.find(options.hoursHSelector).text('2');
                            _this.find(options.hoursLSelector).text('3');
                            var currentDays = (hours);
                            currentDays--;
                        }
                        else {
                            if (currentHours.toString().length == 1) {
                                _this.find(options.hoursHSelector).text('0');
                                _this.find(options.hoursLSelector).text(currentHours.toString());
                            }
                            else {
                                _this.find(options.hoursHSelector).text(currentHours.toString().substr(0, 1));
                                _this.find(options.hoursLSelector).text(currentHours.toString().substr(1, 1));
                            }
                        }
                    }
                    else {
                        if (currentMinutes.toString().length == 1) {
                            _this.find(options.minutesHSelector).text('0');
                            _this.find(options.minutesLSelector).text(currentMinutes.toString());
                        }
                        else {
                            _this.find(options.minutesHSelector).text(currentMinutes.toString().substr(0, 1));
                            _this.find(options.minutesLSelector).text(currentMinutes.toString().substr(1, 1));
                        }
                    }
                }
                else {
                    if (currentSeconds.toString().length == 1) {
                        _this.find(options.secondsHSelector).text('0');
                        _this.find(options.secondsLSelector).text(currentSeconds.toString());
                    }
                    else {
                        _this.find(options.secondsHSelector).text(currentSeconds.toString().substr(0, 1));
                        _this.find(options.secondsLSelector).text(currentSeconds.toString().substr(1, 1));
                    }
                }
            }

            return _this.each(function () {
                if ($(options.daysSelector, _this)[0] != undefined && $(options.daysSelector, _this)[0].innerHTML != "N/A") {
                    setInterval(tick, 1000);
                }
            });
        }
    });

})(jQuery);

$(document).ready(function () {
   // $("div.counter1").countdown();
   // $("div.counter2").countdown();
   // $("div.counter").countdown();
});