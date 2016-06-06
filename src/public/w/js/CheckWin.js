$(function(){
                        var checkWinSize = 0;
                        function checkWin(){
                            var temp = checkWinSize;
                            checkWinSize = ($(".media").width() > 1) ? 1 : 0;
                            if(checkWinSize != temp){
                                $("#win[style]").removeAttr("style").addClass("fix-modal");
                            }
                        }
                        $(window).resize(checkWin);
                    });
