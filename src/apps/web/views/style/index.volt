<!DOCTYPE html>
<html>
    <head>
        {% include "/_elements/meta.volt" %} {# META tags #}

        {# EMTD - we need to move this in the footer, and fix all the inline script #}
        <script src="/w/js/vendor/jquery-1.11.3.min.js"></script>

        <link rel="stylesheet" href="/w/css/main.css"> {# Basic style library #}

        <link rel="stylesheet" href="/w/css/style/style.css"> {# Style of this specific page  #}

        {# FONTS  #}
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>
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
                        <li><a href="#nav-forms">Forms Elements</a></li>
                        <li><a href="#svg">Svg</a></li>
                        <li><a href="#icons">Icons</a></li>
                        <li><a href="#img">Images</a></li>
                        <li><a href="#info">Info Box</a></li>
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
                                    <p>Areas of the project that require a <strong>subnavigation</strong> (like Account and Legal), need to use this code...</p>

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
                                    <p>...and import this css in the SASS related style of that area.</p>
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
                            <h2 class="h3 res">Introduction</h2>
                            <p>This columns system is developed by me, it is a much lighter solution and very similar to the other famous grid sytem (for example <a href="http://getbootstrap.com/2.3.2/scaffolding.html#gridSystem">Twitter Bootstrap</a>). As the famous system is based on 12 column grid system. Each grid need two main components, a definition of the row "cols" and the definition of the size of the column "col1" or up till "col12". The number signify what proportion of the slice of the whole area you want to reserve. The mosts important feature about this column system is that the columns are fluid and they are a perfect use for building a responsive website. The important thing is to keep always the sum of all the number of the columns to be equal to 12 so that the whole space is divided to fill the whole area.</p>

                            <div class="cols">
                                <div class="col6">
                                    <p>Some example code</p>
                                    <pre class="brush:html">
                                        /* This example show how divide a space in 3 parts */
                                        <div class="cols">
                                            <div class="col4"></div>
                                            <div class="col4"></div>
                                            <div class="col4"></div>
                                        </div>

                                        /* This example show how divide a space in 3 parts but different sizes */
                                        <div class="cols">
                                            <div class="col2"></div>
                                            <div class="col4"></div>
                                            <div class="col6"></div>
                                        </div>
                                    </pre>
                                </div>
                                <div class="col6">
                                    <p>The <strong>only exception</strong> to this rule it is the "col20per" or "Column 20 percent". because 12 it is not a space divisible by 5, I created this class name just for that</p>
                                    <pre class="brush:html">
                                        /* This example show how divide a space in 5 parts */
                                        <div class="cols">
                                            <div class="col20per"></div>
                                            <div class="col20per"></div>
                                            <div class="col20per"></div>
                                            <div class="col20per"></div>
                                            <div class="col20per"></div>
                                        </div>
                                    </pre>
                                </div>
                            </div>
                            <br>
                            <hr class="hr">

                            <p>Here some example to show how easy it is to construct a grid system, with and without margins.</p>
                            <div class="cols res">
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
                            <p>The only difference between the two system is in the css, not in the html. Adding a margin to columns can be done in two ways, using Padding or Margin depending if the columns have a coloured background or not.</p>
                                
                            <div class="cols">
                                <div class="col6">
                                    <h3 class="h4">Using Padding</h3>
                                    <p><em>If you don't have any background colors or border applied to the col and it is used just for content</em>, simply use a padding right that points at the col* (* = number).</p>

                                    <pre class="brush:css">
                                        /* ".nameWrapper" it is the name of your div wrapper */
                                        
                                        .nameWrapper > .cols > div{padding:0 10px;}
                                        .nameWrapper > .cols > div:first-child{left:-10px;}
                                        .nameWrapper > .cols > div:last-child{right:-10px;}
                                    </pre>

                                    <pre class="brush:html">
                                        <div class="nameWrapper">
                                            <div class="cols">
                                                <div class="col4"></div> 
                                                <div class="col4"></div> 
                                                <div class="col4"></div> 
                                            </div>
                                        </div>
                                    </pre>

                                    <br>
                                    <div class="box1">
                                        <div class="nameWrapper">
                                            <div class="cols">
                                                <div class="col4">
                                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit tincidunt ut laoreet dolore magna aliquam erat volutpat. 
                                                </div> 
                                                <div class="col4">
                                                    Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                                </div> 
                                                <div class="col4">
                                                    Consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud ut aliquip ex ea commodo consequat.
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col6">
                                    <h3 class="h4">Using Margin</h3>
                                    <p>If you instead have a coloured background specifically limited to the width of the column, than use this method.</p>
                                    <pre class="brush:css">
                                        .nameWrapper{margin-right:-20px;}
                                        .nameWrapper > .cols > div{border-right:20px solid transparent;} /*The color of the border need to match the background color*/
                                    </pre>
                                </div>
                            </div>

                            <hr class="hr">

                            <h3 class="h4">Column height</h3>
                            <p>By default any cols system is used, the columns will have same height, to keep an equally aligned design. Here you can see an example. As you notice even when padding is applied the height doesn't change.</p>
                            <div class="col-height">
                                <div class="cols">
                                    <div class="col4">Lorem ipsum dolor sit amet, in at morbi in lacus cursus a, consequat magna, neque donec, sit at sit, morbi elit justo volutpat sodales. Lorem ullamcorper volutpat nulla in, quas sed tempor arcu nam consequat, suscipit sit non massa suscipit a eleifend, interdum lacinia morbi ligula etiam, sagittis quis maecenas non donec. Auctor luctus et at nec ut et, cum lacinia aliquam. Leo tortor libero vehicula vitae, montes libero, sed sed quis facilisis massa, a congue lacinia luctus pretium erat tellus, autem felis massa mauris mauris hendrerit luctus. Nisl nulla, molestie nisl, vitae praesent a ea in accumsan. Nam placerat sem pharetra sed quam lacus, fames nullam, sociis vestibulum donec congue et vestibulum.</div>
                                    <div class="col4">Nisl nulla, molestie nisl, vitae praesent a ea in accumsan. Nam placerat sem pharetra sed quam lacus, fames nullam, sociis vestibulum donec congue et vestibulum.</div>
                                    <div class="col4">Nisl nulla, molestie nisl, vitae praesent a ea in accumsan. Nam placerat sem pharetra sed quam lacus, fames nullam, sociis vestibulum donec congue et vestibulum.</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <a name="type"></a>
                        <h1 class="title h2">Typography</h1>
                        <div class="content">

                            <p>The main typography used is <strong>Open Sans</strong>. We have only 3 type of font styles: <strong>Normal</strong> (400 weight), <strong>Italic</strong> (400 weight) and <strong>Bold</strong> (700 weight).</p>

                            <p><a href="https://www.google.com/fonts/specimen/Open+Sans">Full Open Sans Alphabet</a></p>
                            
                            <p>This font family is applied to the following tags and classes:
                            <br><strong>body, #content, .input, .select, .textarea</strong></p>
                            <p>The variable defined in SASS is:
                            <br><strong>$basic-font:"Open Sans", helvetica, arial, verdana, sans-serif;</strong></p>
     

                            <hr class="hr">

                            <h2 class="h4">Title Styling and font sizes.</h3>
                            <p>Instead of using the predefined size of the titles, I rather use class related size for the type. This is especially useful for separating the semantic value of an h1 to an h2, but still giving an uniform or more different visual style that would fit more with the design of the page. In Html5, each main tag could have various h1, while having less hierarchy importance between the various titles.</p>

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
                                    <p  style="margin-top:10px;">
                                        Every title (a part from .h0) has a predefined margin-bottom set to 1em.<br>
                                        In the case that you need to reset margin <strong>Use class .res to reset margin.</strong>
                                        <br>Example:
                                    </p>
                                    <pre class="brush: html">
                                        <h1 class="h1 res">Title 30px</h1>
                                    </pre>
                                </div>
                            </div>

                            <hr class="hr">

                            <h2 class="h4">Content and list examples</h3>

                            <div class="cols">
                                <div class="col6">
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <em>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</em> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu <strong>feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim</strong> qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>

                                    <ul class="list">
                                        <li><strong>List with bullet points</strong> (.list)</li>
                                        <li>in hendrerit in vulputate velit esse molestie consequat</li>
                                        <li>vel illum dolore eu feugiat nulla facilisis at</li>
                                    </ul>
                                    <br>

                                    <ul class="no-li">
                                        <li><strong>List without bullet points</strong> (.no-li)</li>
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

                    <section class="box-color">
                        <a name="color"></a>
                        <h1 class="title h2">Color</h1>

                        <div class="content">
                            <p>Those colors are usued for a variety of things, mainly for backgrounds, gradient backgrounds, border colored lines and colored text</p>

                            <div class="cl">
                                <div class="bg-color blk" style="background:#fff;">
                                    #fff  
                                    <br>no variable for White
                                </div>
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

                        <div class="content">
                            <h2 class="h3">Button use guideline</h2>
                            <p>
                                Buttons have a coloure coded inexplicit level of importance across the website, based on their colors. <strong>Big</strong>buttons are used for reinforce importance in the action (buttons on homepage that lead you to Play; button Add to Cart; button that finalise the cart process). <strong>Blue</strong> is our primary colors in actions. <strong>Red</strong> is for delete, remove, clearing or any action that has a removing inpact on the action. <strong>Green</strong> is to differentiate when there are other blue buttons in a page that we want to give a positive feedback in doing this action for the user (widthraw winning money). The buttons with <strong>white backgrounds</strong> are used for secondary actions. <strong>Grey or basic</strong> buttons are used for simple navigation actions like "go back".
                            </p>

                            <hr class="hr">

                            <div class="cols">
                                <div class="col6">
                                    <p>They can be used easily by combining a series of classes with specific use, the most important one to define that an element is a button is <strong>.btn</strong> Most importantly any element can be used to make it look like a button in exactly same style (a link or an input or a span element). You can decide to set a specific width or by default they wrap it up around the length of the word used. Here some examples:</p>

                                    <p><strong>Simple Buttons</strong> those are buttons without any coloured style.</p>
                                    <div class="box-btn">
                                        <a href="javascript:void(0);" class="btn">.btn</a>
                                        <a href="javascript:void(0);" class="btn big">.btn.big</a>
                                        <br><br>
                                        <p>
                                            <strong>Colored buttons</strong> those are the basic colored buttons.
                                        </p>
                                        <a href="javascript:void(0);" class="btn white">.white</a>
                                        <a href="javascript:void(0);" class="btn blue">.blue</a>
                                        <a href="javascript:void(0);" class="btn red">.red</a>
                                        <a href="javascript:void(0);" class="btn green">.green</a>
                                        <a href="javascript:void(0);" class="btn purple">.purple</a>
                                        <br><br>
                                        <p><strong>How to use it:</strong> combine .btn with the class of the style that you want to add.</p>
<pre class="brush: html">
class="btn white"
class="btn blue"
class="btn red"
class="btn green"
class="btn purple"
</pre>

                                        <br><br>
                                        <p>
                                            <strong>.gwy</strong> means: Grey (Border), White (Background), Yellow (Text); and so the other similar buttons they have the same kind of structure and having in common just the grey border and the white background.
                                        </p>
                                        <a href="javascript:void(0);" class="btn gwy">.gwy</a>
                                        <a href="javascript:void(0);" class="btn gwp">.gwp</a>
                                        <a href="javascript:void(0);" class="btn gwr">.gwr</a>
                                        <br><br>
                                        <p>
                                             <strong>.ywy</strong> means: Yellow (Border), White (Background), Yellow (Text); and the others have similar style based on one type of color that match border and text color.
                                        </p>
                                        <a href="javascript:void(0);" class="btn ywy">.ywy</a>
                                        <a href="javascript:void(0);" class="btn gwg">.gwg</a>
                                        <a href="javascript:void(0);" class="btn bwb">.bwb</a>
                                        <a href="javascript:void(0);" class="btn rwr">.rwr</a>
                                    </div>
                                </div>
                                <div class="col6">
                                    <p><strong>Example of possible HTML type of buttons:</strong></p>
                                    <a href="javascript:void(0);" class="btn">Link Button</a>
                                    <label class="btn">
                                        <span class="txt">Label Button</span>
                                        <input type="hidden">
                                    </label>
                                    <button type="submit" clasS="btn">Real Button</button>

<pre class="brush: html">
    <a href="javascript:void(0);" class="btn">Link Button</a>
    <label class="btn">
        <span class="txt">Label</span>
        <input type="hidden">
    </label>
    <button type="submit" clasS="btn">Real Button</button>
</pre>


                                    <p class="res"><strong>Accessibility Concerns</strong></p>
                                    <ul class="list" style="margin:10px 0 15px 15px;">
                                        <li>If it navigates, it is a link. Use link markup with a valid hypertext reference (and add: role="button" to the link)</li>
                                        <li>If it triggers an action, it is a button. Use a BUTTON element</li>
                                    </ul>

                                    <hr class="hr">
                                    <p><strong>Combo Buttons</strong> are buttons that can be bonded together looking like a multiple button with various kind of options, here an example:</p>

                                    <div class="combo">
                                        <a href="javascript:void(0);" class="btn red">left</a><a href="javascript:void(0);" class="btn red">center</a><a href="javascript:void(0);" class="btn red">center</a><a href="javascript:void(0);" class="btn red">right</a>
                                    </div>
                                    <br>

        
                                    <pre class="brush: html"><div class="combo">
    <a href="javascript:void(0);" class="btn red">left</a>
    <a href="javascript:void(0);" class="btn red">center</a>
    <a href="javascript:void(0);" class="btn red">center</a>
    <a href="javascript:void(0);" class="btn red">right</a>
</div>
                                    </pre>
                                    <br>
                                    <p><strong>Note:</strong> ui-link is being added by jquery mobile UI, please ignore that class value</p>

                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <a name="nav-forms"></a>
                        <h1 class="title h2">Forms Elements (*TO DO*)</h1>

                        <div class="content">
                            <p>All the elements of the forms have a light yellow colour, to be more visible on the white background of the website. Some elements have directly being applied</p>

                            <div class="cols">
                                <div class="col6">

                                    Input text
                                    <input type="text" class="input">
                                    Textarea
                                    <textarea class="textarea"></textarea>
                                    <input type="checkbox"> Checkbox

                                </div>
                                <div class="col6">
                                    <h2 class="h3">Currency input field</h2>
                                    <form class="form-currency"><div class="currency">&euro;</div><input type="text" class="input insert"></form>
<br>
<pre class="brush: html">
<form class="form-currency">
    <div class="currency">&euro;</div>
    <input type="text" class="input insert">                           
</form>
</pre>
                                </div>

                        </div>
                    </section>

                    <section class="box-svg">
                        <a name="svg"></a>
                        <h1 class="title h2">SVG</h1>

                        <div class="content">
                            <p>We are using SVG mainly because with the responsive design approach is better to have some graphical elements that can scale and still remain visually appealing. Images require more bandwidth and handling to create different size quality, when SVG can be manipulated with CSS (size and colors for example).</p>

                            <div class="cols">
                                <div class="col6">
                                    <p>The main technique used with SVG called <strong>Stacking</strong>, is inspired to the Sprites technique used with images to reduce Http Requests loading time.</p>

                                    <div class="border1">
                                        <p><strong>Note:</strong> If you want to read more about Stacking here some links of interest:</p>
                                        <ul class="no-li">
                                            <li><a href="https://hofmannsven.com/2013/laboratory/svg-stacking/">https://hofmannsven.com/2013/laboratory/svg-stacking/</a></li>
                                            <li><a href="http://simurai.com/blog/2012/04/02/svg-stacks/">http://simurai.com/blog/2012/04/02/svg-stacks/</a></li>
                                            <li><a href="https://css-tricks.com/svg-fragment-identifiers-work/">https://css-tricks.com/svg-fragment-identifiers-work/</a></li>
                                            <li><a href="https://24ways.org/2014/an-overview-of-svg-sprite-creation-techniques/">https://24ways.org/2014/an-overview-of-svg-sprite-creation-techniques/</a></li>
                                        </ul>
                                    </div>

                                    <p>We are separating the vectors in there are three kind of type for the stacking: Monochromatic (Icons), Coloured and vectors with transparent Shadows.</p>
                                </div>
                                <div class="col6">
                                    <p>Once the SVG is saved from Adobe Illustrator (<a href="http://creativedroplets.com/export-svg-for-the-web-with-illustrator-cc/">more info on how to save on the right format</a>) we need to clean the SVG from not important code. <a href="https://sarasoueidan.com/blog/svgo-tools/">SVGO</a> is the tool to use for this cleaning, there is a <a href="https://jakearchibald.github.io/svgomg/">version online</a> and a version that can be download from <a href="https://github.com/svg/svgo">github</a> and used locally.</p>
                                    <div class="cols res">
                                        <div class="col5">
                                            <br><br><br>
                                            <p align="right">Settings for SVGO for a better compression ---></p>
                                        </div>
                                        <div class="col7" align="center">
                                            <div class="settings"><img src="/w/img/style/setting-svg.jpg" alt="settings"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="no-li svg-img cl">
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#logo"/></svg>
                                    <div>icon.svg#logo</div>
                                </li>
                                <li>
                                    <svg viewBox="-10 150 100 100" class="vector">
                                        <use style="filter:url(#shadow)" transform="scale(4)" xlink:href="/w/svg/icon.svg#logo"/>
                                        <filter height="130%" id="shadow"><feGaussianBlur stdDeviation="1" in="SourceAlpha"/><feOffset result="offsetblur" dy="1" dx=".5"/><feComponentTransfer><feFuncA slope=".5" type="linear"/></feComponentTransfer></filter>
                                        <use transform="scale(4)" xlink:href="/w/svg/icon.svg#logo"/>
                                        <linearGradient gradientTransform="translate(21.248 882.618) scale(4.104)" y2="-161.627" x2="16.777" y1="-135.945" x1="16.777" gradientUnits="userSpaceOnUse" id="d"><stop stop-color="#FFD936" offset="0"/><stop stop-color="#FFF" offset="1"/></linearGradient>
                                    </svg>
                                    <div>icon.svg#logo + shadow *EXPLAIN viewbox*</div>
                                </li>
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#emblem"/></svg>
                                    <div>icon.svg#emblem</div>
                                </li>
                                <li class="black">
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#emblem-bg"/></svg>
                                    <div>icon.svg#emblem-bg</div>
                                </li>
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#lottery-ticket"/></svg>
                                    <div>icon.svg#lottery-ticket</div>
                                </li>
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#monitor"/></svg>
                                    <div>icon.svg#monitor</div>
                                </li>
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#winner-cup"/></svg>
                                    <div>icon.svg#winner-cup</div>
                                </li>
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/number.svg#crown"/></svg>
                                    <div>number.svg#crown</div>
                                </li>
                                <li>
                                    <svg class="vector"><use xlink:href="/w/svg/icon.svg#laurel"/></svg>
                                    <div>icon.svg#laurel</div>
                                </li>
                            </ul>

                            insert code


                            <pre class="brush: html">
                                <svg class="vector"><use xlink:href="/w/svg/icon.svg#emblem"/></svg>
                            </pre>

                            <p></p>

                           .ico difference .vector and .logo


                        </div>
                    </section>

                    <section>
                        <a name="icons"></a>
                        <h1 class="title h2">Icons</h1>

                        <div class="content">
                            <p>Icons are monochromatic vector symbols used across the website. You can resize by using two type of methods:Defining with and height in px (recommended method) or using font-size (not recommended). All the icons used and not used are loaded in a signle file <strong>icon.svg</strong>. <strong>Green marks</strong> on the icons, are icons that are used in the website. <strong>Orange marks</strong> are icons that should be used soon. <strong>Unmarked</strong> are things that might need to be removed.</p>

                            <p>When you try to insert an SVG Icon (add always an ".ico" class and the "v-" prefix with the name of the vector used, so that we could point and make some modifications to those specific icons or all the icons through CSS) always point as href at "/w/svg/icon.svg" and than add "#myNameSvg" to the end. If you are inserting a new icon, be sure to insert that inside the "icon.svg" file correctly.</p>
<pre class="brush: html">
    <svg class="ico v-clover"><use xlink:href="/w/svg/icon.svg#v-clover"/></svg>
</pre>
<br>
                            <div class="cols box-icons res">
                                <div class="col4">
                                    <ul class="no-li">
                                        <li class="green">
                                            <svg class="ico v-clover"><use xlink:href="/w/svg/icon.svg#v-clover"/></svg><span class="txt"> v-clover</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-star"><use xlink:href="/w/svg/icon.svg#v-star"/></svg><span class="txt"> v-star</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-opened25"><use xlink:href="/w/svg/icon.svg#v-opened25"/></svg><span class="txt"> v-opened25</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-lotto-ticket"><use xlink:href="/w/svg/icon.svg#v-lotto-ticket"/></svg><span class="txt"> v-lotto-ticket</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"/></svg><span class="txt"> v-arrow-right</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-arrow-right3"><use xlink:href="/w/svg/icon.svg#v-arrow-right3"/></svg><span class="txt"> v-arrow-right3</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-pencil"><use xlink:href="/w/svg/icon.svg#v-pencil"/></svg><span class="txt"> v-pencil</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-coin-dollar"><use xlink:href="/w/svg/icon.svg#v-coin-dollar"/></svg><span class="txt"> v-coin-dollar</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-mobile2"><use xlink:href="/w/svg/icon.svg#v-mobile2"/></svg><span class="txt"> v-mobile2</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-bubble2"><use xlink:href="/w/svg/icon.svg#v-bubble2"/></svg><span class="txt"> v-bubble2</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-quotes-right"><use xlink:href="/w/svg/icon.svg#v-quotes-right"/></svg><span class="txt"> v-quotes-right</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-key"><use xlink:href="/w/svg/icon.svg#v-key"/></svg><span class="txt"> v-key</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-menu"><use xlink:href="/w/svg/icon.svg#v-menu"/></svg><span class="txt"> v-menu</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-eye"><use xlink:href="/w/svg/icon.svg#v-eye"/></svg><span class="txt"> v-eye</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-star-full"><use xlink:href="/w/svg/icon.svg#v-star-full"/></svg><span class="txt"> v-star-full</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-sad"><use xlink:href="/w/svg/icon.svg#v-sad"/></svg><span class="txt"> v-sad</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"/></svg><span class="txt"> v-info</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"/></svg><span class="txt"> v-checkmark</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-arrow-up2"><use xlink:href="/w/svg/icon.svg#v-arrow-up2"/></svg><span class="txt"> v-arrow-up2</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-arrow-left2"><use xlink:href="/w/svg/icon.svg#v-arrow-left2"/></svg><span class="txt"> v-arrow-left2</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-circle-down"><use xlink:href="/w/svg/icon.svg#v-circle-down"/></svg><span class="txt"> v-circle-down</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-facebook"><use xlink:href="/w/svg/icon.svg#v-facebook"/></svg><span class="txt"> v-facebook</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"/></svg><span class="txt"> v-warning</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col4">
                                    <ul class="no-li">
                                        <li class="green">
                                            <svg class="ico v-question-mark"><use xlink:href="/w/svg/icon.svg#v-question-mark"/></svg><span class="txt"> v-question-mark</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-m"><use xlink:href="/w/svg/icon.svg#v-m"/></svg><span class="txt"> v-m</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-multimedia"><use xlink:href="/w/svg/icon.svg#v-multimedia"/></svg><span class="txt"> v-multimedia</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"/></svg><span class="txt"> v-cart</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-triangle-down"><use xlink:href="/w/svg/icon.svg#v-triangle-down"/></svg><span class="txt"> v-triangle-down</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-play"><use xlink:href="/w/svg/icon.svg#v-play"/></svg><span class="txt"> v-play</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-coin-euro"><use xlink:href="/w/svg/icon.svg#v-coin-euro"/></svg><span class="txt"> v-coin-euro</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-clock"><use xlink:href="/w/svg/icon.svg#v-clock"/></svg><span class="txt"> v-clock</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-tablet"><use xlink:href="/w/svg/icon.svg#v-tablet"/></svg><span class="txt"> v-tablet</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-user"><use xlink:href="/w/svg/icon.svg#v-user"/></svg><span class="txt"> v-user</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-spinner"><use xlink:href="/w/svg/icon.svg#v-spinner"/></svg><span class="txt"> v-spinner</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-cog"><use xlink:href="/w/svg/icon.svg#v-cog"/></svg><span class="txt"> v-cog</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-earth"><use xlink:href="/w/svg/icon.svg#v-earth"/></svg><span class="txt"> v-earth</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-eye-blocked"><use xlink:href="/w/svg/icon.svg#v-eye-blocked"/></svg><span class="txt"> v-eye-blocked</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-heart"><use xlink:href="/w/svg/icon.svg#v-heart"/></svg><span class="txt"> v-heart</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-plus"><use xlink:href="/w/svg/icon.svg#v-plus"/></svg><span class="txt"> v-plus</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-cancel-circle"><use xlink:href="/w/svg/icon.svg#v-cancel-circle"/></svg><span class="txt"> v-cancel-circle</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-exit"><use xlink:href="/w/svg/icon.svg#v-exit"/></svg><span class="txt"> v-exit</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-arrow-right2"><use xlink:href="/w/svg/icon.svg#v-arrow-right2"/></svg><span class="txt"> v-arrow-right2</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-circle-up"><use xlink:href="/w/svg/icon.svg#v-circle-up"/></svg><span class="txt"> v-circle-up</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-circle-left"><use xlink:href="/w/svg/icon.svg#v-circle-left"/></svg><span class="txt"> v-circle-left</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-twitter"><use xlink:href="/w/svg/icon.svg#v-twitter"/></svg><span class="txt"> v-twitter</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col4">
                                    <ul class="no-li">
                                        <li class="green">
                                            <svg class="ico v-star-out"><use xlink:href="/w/svg/icon.svg#v-star-out"/></svg><span class="txt"> v-star-out</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-email64"><use xlink:href="/w/svg/icon.svg#v-email64"/></svg><span class="txt"> v-email64</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-pull-down"><use xlink:href="/w/svg/icon.svg#v-pull-down"/></svg><span class="txt"> v-pull-down</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-hourglass"><use xlink:href="/w/svg/icon.svg#v-hourglass"/></svg><span class="txt"> v-hourglass</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-triangle-up"><use xlink:href="/w/svg/icon.svg#v-triangle-up"/></svg><span class="txt"> v-triangle-up</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-bullhorn"><use xlink:href="/w/svg/icon.svg#v-bullhorn"/></svg><span class="txt"> v-bullhorn</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-coin-pound"><use xlink:href="/w/svg/icon.svg#v-coin-pound"/></svg><span class="txt"> v-coin-pound</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-display"><use xlink:href="/w/svg/icon.svg#v-display"/></svg><span class="txt"> v-display</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-bubble"><use xlink:href="/w/svg/icon.svg#v-bubble"/></svg><span class="txt"> v-bubble</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-quotes-left"><use xlink:href="/w/svg/icon.svg#v-quotes-left"/></svg><span class="txt"> v-quotes-left</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-search"><use xlink:href="/w/svg/icon.svg#v-search"/></svg><span class="txt"> v-search</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-bin"><use xlink:href="/w/svg/icon.svg#v-bin"/></svg><span class="txt"> v-bin</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-flag"><use xlink:href="/w/svg/icon.svg#v-flag"/></svg><span class="txt"> v-flag</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-star-empty"><use xlink:href="/w/svg/icon.svg#v-star-empty"/></svg><span class="txt"> v-star-empty</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-smile"><use xlink:href="/w/svg/icon.svg#v-smile"/></svg><span class="txt"> v-smile</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-minus"><use xlink:href="/w/svg/icon.svg#v-minus"/></svg><span class="txt"> v-minus</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-cross"><use xlink:href="/w/svg/icon.svg#v-cross"/></svg><span class="txt"> v-cross</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-shuffle"><use xlink:href="/w/svg/icon.svg#v-shuffle"/></svg><span class="txt"> v-shuffle</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-arrow-down2"><use xlink:href="/w/svg/icon.svg#v-arrow-down2"/></svg><span class="txt"> v-arrow-down2</span>
                                        </li>
                                        <li>
                                            <svg class="ico v-circle-right"><use xlink:href="/w/svg/icon.svg#v-circle-right"/></svg><span class="txt"> v-circle-right</span>
                                        </li>
                                        <li class="green">
                                            <svg class="ico v-google-plus"><use xlink:href="/w/svg/icon.svg#v-google-plus"/></svg><span class="txt"> v-google-plus</span>
                                        </li>
                                        <li class="orange">
                                            <svg class="ico v-wordpress"><use xlink:href="/w/svg/icon.svg#v-wordpress"/></svg><span class="txt"> v-wordpress</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <a name="img"></a>
                        <h1 class="title h2">Images</h1>
                        <div class="content">

                            <p>responsive images, css images as well</p>

                            <p>sprites</p>
                        </div>
                    </section>

                    <section>
                        <a name="info"></a>
                        <h1 class="title h2">Info Box</h1>
                        <div class="content">
                            <p>info box</p>
                        </div>
                    </section>


                    *tabs*
                    *react tooltip*
                    *modal*

                  <section>
                    <h1 class="title h2">Things to do</h1>
                    <ul class="list">
                        <li>Adjust the .list when we have bullet points</li>
                        <li>Fix all the buttons (mantein one style of button and one for navigation links)</li>
                        <li>Fix the automatic width of the inputs by adding a simple class</li>
                        <li>Try to reduce the amount of SVG elements used.</li>
                        <li>Renaming icons with v- prefix (removing v- prefix in something)</li>
                        <li>Remove icons not used</li>
                        <li>Store the font Open Sants on our server and test if it is faster to load</li>
                        <li>Remove all the margin-bottom from paragraphs with ".res"</li>
                        <li>Box-basic and wrapper size, is there a way to reduce the html? or size?</li>
                    </ul>
                  </section>                    

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