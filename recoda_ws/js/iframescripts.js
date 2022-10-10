"use strict";
document.addEventListener("DOMContentLoaded", () => {
  
  var disablePaddingDrag = `.ws_pm_disabled .rb-overlay{pointer-events: none;}`;
  var xModeCSS = `  html.ws-x-on * {
                      outline: 1px solid rgba(0, 0, 0, 0.3) !important;
                      outline-offset: -1px !important;
                      -webkit-filter: grayscale(1);
                      filter: grayscale(1); 
                    
                    }
                    html.ws-x-on .ct-active {
                      outline: 2px solid rgba(0, 0, 0, 0.8) !important;          
                    }`;
  

var css = `
  ${disablePaddingDrag}
  ${xModeCSS}
  :root{--ws_offset:32px;--ws_max_width:1120px;--ws_columns:12;--ws_gutter:10px;--ws_color:hsla(286, 51%, 44%, 0.25);--re_offset:var(--offset, var(--ws_offset));--re_max_width:var(--max_width, var(--ws_max_width));--re_columns:var(--columns, var(--ws_columns));--re_gutter:var(--gutter, var(--ws_gutter));--re_color:var(--grid-color, var(--ws_color))}@media (max-width:1120px){:root{--ws_offset:32px;--ws_gutter:16px}}@media (max-width:768px){:root{--ws_columns:2;--ws_offset:32px;--ws_gutter:16px}}@media (max-width:460px){:root{--ws_columns:2;--ws_offset:32px;--ws_gutter:16px}}:root{--repeating-width:calc(100% / var(--re_columns));--column-width:calc((100% / var(--re_columns)) - var(--re_gutter));--background-width:calc(100% + var(--re_gutter));--background-columns:repeating-linear-gradient(
    to right,
    var(--re_color),
    var(--re_color) var(--column-width),
    transparent var(--column-width),
    transparent var(--repeating-width)
  )}.tf-filled{position:relative;z-index:0}.tf-filled:before{position:absolute;top:0;right:0;bottom:0;left:0;margin-right:auto;margin-left:auto;width:calc(100% - (2 * var(--re_offset)));max-width:var(--re_max_width);min-height:100vh;content:'';background-image:var(--background-columns);background-size:var(--background-width) 100%;background-position:0;z-index:1000;pointer-events:none}.tf-filled:after{content:var(--media-query);position:fixed;top:1rem;left:1rem;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;color:var(--re_color-text)}.tf-filled body{height:100%}html.ws-tiny-scroll{scrollbar-width: thin;}html.ws-tiny-scroll::-webkit-scrollbar {width: 8px;}`;

  let style = '<style>'+css+'</style>';

  document.head.insertAdjacentHTML("beforeend", style);



});