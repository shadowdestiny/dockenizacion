<!DOCTYPE html>
<html>
    <head>
        {% include "_elements/meta.volt" %} {# META tags #}
        {% include "_elements/js-lib.volt" %} {# JS libraries #}

        <script src="/js/style-guide/shCore.js"></script>
        <script src="/js/style-guide/shBrushXml.js"></script>
        <script src="/js/style-guide/shAutoloader.js"></script>
        <script>
            $(function(){
                SyntaxHighlighter.all()

                var target = $(".nav").offset().top,
                    timeout = null;

                $(window).scroll(function(){
                    if (!timeout){
                        timeout = setTimeout(function() {
                            console.log('scroll');            
                            clearTimeout(timeout);
                            timeout = null;
                            if ($(window).scrollTop() >= target) {
                                $(".nav").addClass('top');
                            }else{
                                $(".nav").removeClass('top');
                            }
                        }, 250);
                    }
                });
            });
        </script>



        <link rel="stylesheet" href="/css/main.css"> {# Basic style library #}

        <link rel="stylesheet" href="/css/style-guide/style.css"> {# Style of this specific page  #}

        {# FONTS  #}
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>
    </head>

    <body class="style-guide">

        <div class="main">
            <h1 class="main-title h1">Euromillions.com Style Guide</h1>
            <p>This document is a guide to the mark-up styles used throughout any site project.</p>
            <br><br>

            <div class="cols divider">
                <div class="col3">
                    <ul class="nav no-li">
                        <li><a href="#type" data-ajax="false">Typography</a></li>
                        <li><a href="#color" data-ajax="false">Color</a></li>
                        <li><a href="#btn" data-ajax="false">Buttons</a></li>
                    </ul>
                </div>
                <div class="col9">
                    <section>
                        <a name="type"></a>
                        <h1 class="title h2">Typography</h1>

                        <div class="content">
                            <div class="cols">
                                <div class="col6">
                                    <strong class="h3">Font family: Open Sans</strong>
                                    <br><br>The quick brown fox jumps over the lazy dog
                                    <br><br>1234567890!@#$%^&*()
                                    <br>ABCDEFGHIJKLMNOPQRSTUVWXYZ
                                    <br>abcdefghijklmnopqrstuvwxyz
                                </div>
                                <div class="col6">
                                    <p>The font is applied to the following tags and classes:
                                    <br><strong>body, #content, .input, .select, .textarea</strong></p>
                                    <p>The variable defined in SASS is:
                                    <br><strong>$basic-font:"Open Sans", helvetica, arial, verdana, sans-serif;</strong></p>
                                </div>
                            </div>
                        </div>

                        <hr class="hr">

                        <div class="content">
                            <div class="cols">
                                <div class="col6">
                                    <h1 class="h0">Title 54px</h1>
                                    <h1 class="h1">Title 30px</h1>
                                    <h1 class="h2">Title 22px </h1>
                                    <h1 class="h3">Title 18px</h1>
                                    <h1 class="h4">Title 15px </h1>
                                    <h1 class="h5">Title 14px</h1>
                                    <h1 class="h6">Title 12px</h1>
                                    <p>$basic-font 14px</p>
                                    <p class="small-txt">$small 12px</p>
                                </div>
                                <div class="col6">
                                    <pre class="brush: html">
                                        <h1 class="h0">Title 54px</h1>
                                        <h1 class="h1">Title 30px</h1>
                                        <h1 class="h2">Title 22px </h1>
                                        <h1 class="h3">Title 18px</h1>
                                        <h1 class="h4">Title 15px </h1>
                                        <h1 class="h5">Title 14px</h1>
                                        <h1 class="h6">Title 12px</h1>
                                        <p>$basic-font 14px</p>
                                        <p class="small-txt">$small 12px</p>
                                    </pre>
                                </div>
                            </div>


                            <hr class="hr">


                            <div class="cols">
                                <div class="col6">
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <em>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</em> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu <strong>feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim</strong> qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>

                                    <ul class="list">
                                        <li><strong>List with bullet points</strong></li>
                                        <li>in hendrerit in vulputate velit esse molestie consequat</li>
                                        <li>vel illum dolore eu feugiat nulla facilisis at</li>
                                    </ul>

                                    <ul class="no-li">
                                        <li><strong>List without bullet points</strong></li>
                                        <li>in hendrerit in vulputate velit esse molestie consequat</li>
                                        <li>vel illum dolore eu feugiat nulla facilisis at</li>
                                    </ul>
                                </div>
                                <div class="col6">
                                    <pre class="brush: html">
                                         <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <em>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</em> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu <strong>feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim</strong> qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>

                                        <ul class="list">
                                            <li>With bullet points</li>
                                            <li>in hendrerit in vulputate velit esse molestie consequat</li>
                                            <li>vel illum dolore eu feugiat nulla facilisis at</li>
                                        </ul>

                                        <ul class="no-li">
                                            <li>Without bullet points</li>
                                            <li>in hendrerit in vulputate velit esse molestie consequat</li>
                                            <li>vel illum dolore eu feugiat nulla facilisis at</li>
                                        </ul>
                                    </pre>
                                </div>
                            </div>

                        </div>
                    </section>

                    <section>
                        <a name="color"></a>
                        <h1 class="title h2">Color</h1>

                        <div class="content">
                            <p>Those colors are usued for a variety of things, mainly for backgrounds, gradient backgrounds, border colored lines and colored text</p>

                            <div class="cl">
                                <div class="bg-color grey-lighter blk">
                                    $grey-lighter
                                    <br>#ddd
                                </div>
                                <div class="bg-color grey-light">
                                    $grey-light
                                    <br>#aaa
                                </div>
                                <div class="bg-color grey">
                                    $grey
                                    <br>#777
                                </div>
                                <div class="bg-color grey-dark">
                                    $grey-dark
                                    <br>#444
                                </div>
                                <div class="bg-color black">
                                    $black
                                    <br>#333
                                </div>
                            </div>
                            <div class="cl">
                                <div class="bg-color yellow-lighter blk">
                                    $yellow-lighter
                                    <br>#fffeec
                                </div>
                                <div class="bg-color yellow">
                                    $yellow 
                                    <br>#efc048
                                </div>
                                <div class="bg-color yellow-dark">
                                    $yellow-dark
                                    <br>#EBB019
                                </div>
                                <div class="bg-color yellow-darker">
                                    $yellow-darker
                                    <br>#916C0D
                                </div>
                            </div>

                            <div class="cl">
                                <div class="bg-color purple">
                                    $purple 
                                    <br>#AE5279
                                </div>
                                <div class="bg-color purple-dark">
                                    $purple-dark
                                    <br>#7A3955
                                </div>
                                <div class="bg-color purple-darker">
                                    $purple-darker
                                    <br>#351925
                                </div>
                            </div>

                            <div class="cl">
                                <div class="bg-color green-light">
                                    $green-light
                                    <br>#00AB00
                                </div>
                                <div class="bg-color green">
                                    $green
                                    <br>#007800
                                </div>
                                <div class="bg-color green-dark">
                                    $green-dark
                                    <br>#004500
                                </div>
                            </div>
                            <div class="cl">
                                <div class="bg-color blue">
                                    $blue
                                    <br>#372c72
                                </div>
                                <div class="bg-color blue-dark">
                                    $blue-dark
                                    <br>#1C173B
                                </div>
                            </div>
                            <div class="cl">
                                <div class="bg-color red">
                                    $red
                                    <br>#c22
                                </div>
                            </div>
                            <div class="cl">
                                <p>Color used only in combination with .box for various kind of information
                                <div class="bg-color bg-green blk">
                                    $bg-green (success box)
                                    <br>#e0ffe4
                                </div>
                                <div class="bg-color bg-red blk">
                                    $bg-red (error box)
                                    <br>#ffe0e0
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <a name="btn"></a>
                        <h1 class="title h2">Buttons</h1>
                        
                        <div class="content box-btn">
                            <a href="javascript:void(0);" class="btn">.btn</a>
                            <a href="javascript:void(0);" class="btn big">.btn.big</a>
                            <br><br>
                            <a href="javascript:void(0);" class="btn blue">.blue</a>
                            <a href="javascript:void(0);" class="btn red">.red</a>
                            <a href="javascript:void(0);" class="btn green">.green</a>
                            <a href="javascript:void(0);" class="btn purple">.purple</a>
                            <br><br>
                            <a href="javascript:void(0);" class="btn gwy">.gwy</a>
                            <a href="javascript:void(0);" class="btn gwp">.gwp</a>
                            <a href="javascript:void(0);" class="btn ywy">.ywy</a>
                            <br><br>
                            <a href="javascript:void(0);" class="btn gwr">.gwr</a>
                            <a href="javascript:void(0);" class="btn gwg">.gwg</a>
                            <a href="javascript:void(0);" class="btn bwb">.bwb</a>
                            <a href="javascript:void(0);" class="btn rwr">.rwr</a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>