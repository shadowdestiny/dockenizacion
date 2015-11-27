<!DOCTYPE html>
<html>
    <head>
        {% include "/_elements/meta.volt" %} {# META tags #}

        {# EMTD - we need to move this in the footer, and fix all the inline script #}
        <script src="/w/js/vendor/jquery-1.11.3.min.js"></script>

        <link rel="stylesheet" href="/w/css/main.css"> {# Basic style library #}

        <link rel="stylesheet" href="/w/css/style/style.css"> {# Style of this specific page  #}

        {# FONTS  #}
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>
    </head>

    <body class="style-guide">

        <div class="main">
            <h1 class="main-title h1">Euromillions.com Style Guide</h1>
            <p>This document is a guide to the mark-up styles used throughout any site project. <strong>Last updated: {{ date('Y-m-d') }}</strong></p>

            <div class="cols divider">
                <div class="col2">
                    <ul class="nav no-li" data-ajax="false">
                        <li><a href="#overview">Overview</a></li>
                        <li><a href="#breakpoints">CSS Media Queries</a></li>
                        <li><a href="#layout">Layout</a></li>
                        <li><a href="#columns">Columns</a></li>
                        <li><a href="#type">Typography</a></li>
                        <li><a href="#color">Color</a></li>
                        <li><a href="#btn">Buttons</a></li>
                        <li><a href="#svg">Svg</a></li>
                        <li><a href="#icons">Icons</a></li>
                        <li><a href="#img">Images</a></li>
                        <li><a href="#info">Info</a></li>
                    </ul>
                </div>
                <div class="col10">
                    <section>
                        <a name="overview"></a>
                        <h1 class="title h2 green">Overview</h1>

                        <div class="content">
                            <p class="intro">
                                <strong>Euromillions.com Style Guide</strong> is being built with the intention to help developers of the website in using and understanding the code and design used to build upon it, re-using existing code and quicken development. This same webpage is built and interconnected with the same resource code of the website (CSS and JS). We used two technologies to help us with this <a href="https://docs.phalconphp.com/en/latest/reference/volt.html">Volt</a> and <a href="http://sass-lang.com/">Sass</a>
                            </p>
                            <div class="cols">
                                <div class="col6">
                                    <h2 class="h3 res">Volt</h2>
                                    <p>
                                        The file <strong>main.volt</strong> (under "apps/web/views/") is the layout that define the structure of the whole website. It has several Includes files and (% Block %) elements (each one commented on the purpose). The (% Block %) element allows to use recursive includes to be able to inject extra HTML, JS and CSS in the right areas of the html page from a child page in the main structure.
                                    </p>

                                    <h3 class="h4">How to combine "include" and "variable" to highlight a navigation link</h3>
                                    <ol class="ol">
                                        <li>
                                            <p>Open the include that contain the whole list of links that rapresent the navigation (In this case "_elements/header.volt")</p>
                                        </li>
                                        <li>
                                            <p>Add to each element a class name and value</p>
                                            <pre class="brush: html">
                                                <a href="#" class="(% if activeNav.myClass == 'play' %)active(% endif %)">Play</a>
                                            </pre>
                                        </li>
                                        <li>
                                            <p>Before the include is being retrospectively included set the variable, so that if the file "play.volt" is including the header with the navigation it knows which area to activate. Repeat the same process for all the other areas "index.volt", "numbers.volt" and "help.volt" when included.</p>
                                            <pre class="brush: html">
                                                (% set activeNav='{"myClass":"play"}'|json_decode %)
                                                (% include "_elements/header.volt" %)
                                            </pre>
                                        </li>
                                        <li>
                                            <p>Set in the css the style that you need to make ".active" highlighted</p>
                                        </li>
                                    </ol>

                                    <p><strong>Note:</strong> "{ }" with "%" are being substituted with "(% %)" to show some example</p>
                                </div>
                                <div class="col6">
                                    <h2 class="h3 res">Sass</h2>

                                    <h3 class="h4 res">Structure of CSS used</h3>
                                    <p>CSS is being structured in 3 kind of groups</p>
                                    <ul class="ul">
                                        <li>
                                            <h4 class="h4 res">Global</h4>
                                            <p class="res">All CSS in this category are unified in a single CSS archive and minified. This file it will be cached.</p>
                                            <ul class="ul">
                                                <li><p class="res">"main.scss" load all the global css (vendors CSS and column system)</p></li>
                                                <li><p class="res">"basic.scss" holds all the generic styles that are used across the whole project</p></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h4 class="h4 res">Area or Page Related</h4>
                                            <p>Css Area related like "account.scss", "legal.scss" holds information used across the whole related area. Page Related css like "play.scss" or "help.scss" are used specifically for a single related page. Those files will be cached.</p>
                                        </li>
                                        <li>
                                            <h4 class="h4 res">Component Related</h4>
                                            <p>Some component are loaded directly using "style" tag in the code, or loaded as a SASS import directly in the area that are used like "tooltip.scss" imported at the top of "play.scss". This kind of import will be cached but it could lead to duplicate imports if used inappropriately.</p>
                                        </li>
                                    </ul>
                                    
                                    <h3 class="h4">Including SCSS libraries</h3>
                                    <p>All the CSS that need <strong>to import default values</strong> (like a specific kind of colors, or font-size) need to have an import at the top of the page</p>
                                    <pre class="brush: css">
                                        @import "variable";
                                    </pre>
                                    <br><p>
                                        To import instead compatible across browser CSS3 features (like "linear gradients") use the import from <a href="http://compass-style.org/">Compass CSS Framework</a> 
                                    </p>
                                    <pre class="brush: css">
                                        @import "compass/css3";
                                    </pre>
                                </div>
                            </div>

                            <hr class="hr">

                            <h2 class="h2">Javascript, Images and SVG organization</h2>
                            <p>All <strong>the files location</strong> related to Euromillions.com website are kept inside the public/w/ (w stand for "web"), instead the files related to the admin of the website are inside the public/a/ (a stand for "admin").</p>

                            <div class="cols">
                                <div class="col6">
                                    <h3 class="h3">Javascript</h3>
                                    <p>All the JS file need to be YET compressed in the smallest ammount of files. We created the folder "vendor" to keep all the files imported from other projects of libraries.</p>
                                    <h3 class="h3">Images</h3>
                                    <p class="res">The images are divided per folder and related to the page with the same name. Images on the root are global images like the logo or the icon sprites that are used everywhere.  Each image that is not a background (because of responsive purpose) has 3 sizes: Small (end tag "-sm"), Normal (end name @1x.jpg) Large for retina (end name @2x.jpg).</p>
                                </div>
                                <div class="col6">
                                    <h3 class="h3">SVG</h3>
                                    <p>All the SVG used are minified and cleaned from unimportant code. There are 3 types of SVG:</p>
                                    <ul class="ul">
                                        <li><p class="res"><strong>Icons</strong> are not just icons of monochromatic vectors, but as well colored ones that can be used across the website. All those svg are all loaded as a Sprite for images doe, inside a single file "icons.svg" &nbsp; <a href="#icons">Look at the list of the icons used</a></p></li>
                                        <li><p class="res"><strong>Page specific SVG</strong> that are loaded only in one particular page like "index.svg" and "number.svg"</p></li>
                                        <li><p class="res"><strong>SVG with Gradients and  Trasparency</strong>, those are loaded individually like the ones inside the public/w/svg/home folder because gradients have a tricky syntax to interpeter using the SVG Spriting technique</p></li>
                                    </ul>
                                </div>
                            </div>
    
                            <hr class="hr">

                            <h2 class="h2">The folder "_elements" and include files</h2>
                            <p>All the files inside the folder "_elements" are used as include because re-used across the website more than once. Instead the files that have as prefix as underscore like "_star.volt" inside apps/web/views/play/ are included files related to the play page, they might be used multiple times inside the same page or across some other specific pages.</p>
                        </div>
                    </section>

                    <section>
                        <a name="breakpoints"></a>
                        <h1 class="title h2">CSS Media Queries</h1>

                        <div class="content">
                            <p>The website at the moment isn't built as mobile first strategy, so the breakpoints are all loading from desktop to mobile (the best approach should be the opposite). We plan to change that and swap things around. We are not using at the moment any print specific styles.</p>
                            <p>At the moment the main breakpoints are the followings (a part some small and specific exception)</p>
                            <div class="cols">
                                <pre class="brush:css">
                                    /* Large Desktops */
                                    @media only screen and (max-width:1200px){}

                                    /* Medium Devices, Desktops */
                                    @media only screen and (max-width:992px){}

                                    /* Small Devices, Tablets */
                                    @media only screen and (max-width:768px){}

                                    /* Extra Small Devices, Phones */
                                    @media only screen and (max-width:480px){}

                                    /* Custom, iPhone Retina */
                                    @media only screen and (max-width:320px){}
                                </pre>
                            </div>
                            <h2 class="h3">Media Queries for retina images</h2>
                            <p>All the images inserted in this very long condition, will not be downloaded unless you have a Retina device. perfect for background images inserted in the CSS.</p>
                            <pre class="brush:css">
                                /* Retina Only */
                                @media only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min-device-pixel-ratio: 1.5), only screen and (min-resolution: 144dpi), only screen and (min-resolution: 1.5dppx){/* Insert here the Background Url CSS */}
                            </pre>
                        </div>
                    </section>

                    <section>
                        <a name="layout"></a>
                        <h1 class="title h2">Layout</h1>
                        <div class="content">
                            <div class="cols">
                                <div class="col6">
                                    <p>The div <strong>wrapper</strong> is used to define a limit to the width of the page. Its standard max-width size is 1180px, but it will become responsive if the page will result to be smaller, and when getting to mobile size it will expand automatically to the edges of the mobile screen looking like an uniformed background.</p>
                                    <pre class="brush:html">
                                        <div id="content">
                                            <div class="wrapper">...</div> /* Max-Width 1180px  */
                                        </div>
                                    </pre>
                                    <br>
                                    <p>The div <strong>box-basic</strong> is used to define the style of the container of content, each Box-Basic has a yellow border, with white background and paddings set.</p>
                                    <p>There are three sizes. Example on how to use it are listed in the code below.</p> 

                                    <pre class="brush:html">
                                        Used 
                                        <div class="box-basic">...</div> /* No width defined it adapt to his container */

                                        <div class="box-basic medium">...</div> /* Max-width 760px -  Used in cart area */

                                        <div class="box-basic small">...</div> /* Max-width 400px - Used for Login or Review transaction */
                                    </pre>

                                </div>
                                <div class="col6">
                                    <p>Areas of the project that require a <strong>subnavigation</strong> (like Account and Legal), need to use this code</p>

                                    <pre class="brush:html">
                                        <div class="wrapper">
                                            <div class="nav box-basic">
                                                *Navigation*
                                            </div>
                                            <div class="content box-basic">
                                                *Content*
                                            </div>
                                        </div>
                                    </pre>

                                    <br>
                                    <p>And import this css in the SASS related style of that area</p>
                                    <pre class="brush:css">
                                        @import "_elements/with-subnav.scss";
                                    </pre>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <a name="columns"></a>
                        <h1 class="title h2">Columns</h1>

                        <div class="content">
                          <div class="cols">
                                <div class="col6">
                                    <div class="box-col1">
                                        <h2 class="h3">Columns (without margins)</h2>
                                        <div class="cols">
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col2">&nbsp;</div>
                                            <div class="col4">&nbsp;</div>
                                            <div class="col6">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col7">&nbsp;</div>
                                            <div class="col5">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col8">&nbsp;</div>
                                            <div class="col4">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col9">&nbsp;</div>
                                            <div class="col3">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col10">&nbsp;</div>
                                            <div class="col2">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col11">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col12">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col6">
                                    <div class="box-col2">
                                        <h2 class="h3">Columns (with margins)</h2>
                                        <div class="cols">
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col2">&nbsp;</div>
                                            <div class="col4">&nbsp;</div>
                                            <div class="col6">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col7">&nbsp;</div>
                                            <div class="col5">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col8">&nbsp;</div>
                                            <div class="col4">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col9">&nbsp;</div>
                                            <div class="col3">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col10">&nbsp;</div>
                                            <div class="col2">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col11">&nbsp;</div>
                                            <div class="col1">&nbsp;</div>
                                        </div>
                                        <div class="cols">
                                            <div class="col12">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div> 


                            <hr class="hr">

                          xxxx

                        </div>
                    </section>

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


                            <hr class="hr">

                            <div class="cols">
                                <div class="col6">
                                    <h1 class="h0">Title 54px</h1>
                                    <h1 class="h1">Title 30px</h1>
                                    <h1 class="h2">Title 22px</h1>
                                    <h1 class="h3">Title 18px</h1>
                                    <h1 class="h4">Title 15px</h1>
                                    <h1 class="h5">Title 14px</h1>
                                    <h1 class="h6">Title 12px</h1>
                                    <p>$basic-font 14px</p>
                                    <p class="small-txt">$small 12px</p>
                                </div>
                                <div class="col6">
                                    <pre class="brush: html">
                                        <h1 class="h0">Title 54px</h1>
                                        <h1 class="h1">Title 30px</h1>
                                        <h1 class="h2">Title 22px</h1>
                                        <h1 class="h3">Title 18px</h1>
                                        <h1 class="h4">Title 15px</h1>
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
                                <div class="bg-color grey-lighter2 blk">
                                    $grey-lighter
                                    <br>#ddd
                                </div>
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

                                <div class="bg-color yellow-light blk">
                                    $yellow 
                                    <br>#ffcf58
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

                        <p>some content explanation</p>
                        
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

                        * Combo buttons *
                    </section>

                    <section>
                        <a name="svg"></a>
                        <h1 class="title h2">SVG</h1>

                        <p>some content explanation</p>
                    </section>

                    <section>
                        <a name="icons"></a>
                        <h1 class="title h2">Icons</h1>

                        <p>some content explanation</p>
                    </section>

                    <section>
                        <a name="img"></a>
                        <h1 class="title h2">Images</h1>

                        <p>responsive images, css images as well</p>

                        <p>sprites</p>
                    </section>

                    <section>
                        <a name="info"></a>
                        <h1 class="title h2">Info Box</h1>

                        <p>info box</p>
                    </section>

                    *tabs*
                    *tipr/tooltip*
                    *modal*
                </div>
            </div>
        </div>

        {% set style='{"guide": "on"}'|json_decode %} 
        {% include "/_elements/js-lib.volt" %} {# JS libraries #}

        <script src="/w/js/style/shCore.js"></script>
        <script src="/w/js/style/shBrushXml.js"></script>
        <script src="/w/js/style/shBrushCss.js"></script>
        <script src="/w/js/style/shAutoloader.js"></script> 
        <script>
            $(function(){
                SyntaxHighlighter.all()

                var target = $(".nav").offset().top,
                    timeout = null;

                $(window).scroll(function(){
                    if (!timeout){
                        timeout = setTimeout(function() {
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
    </body>
</html>