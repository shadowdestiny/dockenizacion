<script>
    var link = "";
    function showInfo() {
        if (checkSize() < 2) {
            $(".signup").hover(function (event) {
                $(".my-left").fadeIn();
            }, function () {
                $(".my-left").fadeOut();
            });

            $(".connect").hover(function (event) {
                $(".my-right").fadeIn();
            }, function () {
                $(".my-right").fadeOut();
            });
        } else {
            $(".signup, .connect").unbind('mouseenter mouseleave');
        }
    }
    $(function () {
        $(".tabs-menu a").click(function (event) {
            event.preventDefault();
            if (!$(this).hasClass("active")) {
                $(this).siblings().removeClass("active");
                $(this).addClass("active");
                var tab = $(this).attr("href");
                $(".tab-content").not(tab).css("display", "none");
                $(tab).toggle();
            }
            link = this;
        });
        showInfo();
        $(window).resize(showInfo);
    });
</script>