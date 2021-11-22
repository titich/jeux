	<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="title" content="<?php echo $headTitle[$lang]?>">
   <meta name="description" content="<?php echo $headDescription[$lang]?>">

   <title><?php echo $headTitle[$lang]?></title>
   <style>
   
@import 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700';

/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */

/*! normalize.css v3.0.3 | MIT License | github.com/necolas/normalize.css */

html {
   font-family: sans-serif;
   -webkit-text-size-adjust: 100%;
   -ms-text-size-adjust: 100%;
}

body {
   margin: 0;
}

details,footer,header,main,nav {
   display: block;
}

[hidden],template {
   display: none;
}

a {
   background-color: transparent;
}

a:active,a:hover {
   outline: 0;
}

h1 {
   margin: .67em 0;
   font-size: 2em;
}

hr {
   height: 0;
   -webkit-box-sizing: content-box;
   -moz-box-sizing: content-box;
   box-sizing: content-box;
}

button {
   margin: 0;
   font: inherit;
   color: inherit;
}

button {
   overflow: visible;
}

button {
   text-transform: none;
}

button {
   -webkit-appearance: button;
   cursor: pointer;
}

button[disabled] {
   cursor: default;
}

button::-moz-focus-inner {
   padding: 0;
   border: 0;
}

/*! Source: https://github.com/h5bp/html5-boilerplate/blob/master/src/css/main.css */

@media print {
   *,  :after,  :before {
      color: #000;
      text-shadow: none;
      background: 0 0;
      -webkit-box-shadow: none;
      box-shadow: none;
   }

   a,  a:visited {
      text-decoration: underline;
   }

   a[href]:after {
      content: " (" attr(href) ")";
   }

   a[href^="javascript:"]:after,  a[href^="#"]:after {
      content: "";
   }

   h2,  h3,  p {
      orphans: 3;
      widows: 3;
   }

   h2,  h3 {
      page-break-after: avoid;
   }

   .navbar {
      display: none;
   }
}

@font-face {
   font-family: 'Glyphicons Halflings';
   src: url(/resources/public/dtc/fonts/glyphicons-halflings-regular.eot);
   src: url(/resources/public/dtc/fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'),url(/resources/public/dtc/fonts/glyphicons-halflings-regular.woff2) format('woff2'),url(/resources/public/dtc/fonts/glyphicons-halflings-regular.woff) format('woff'),url(/resources/public/dtc/fonts/glyphicons-halflings-regular.ttf) format('truetype'),url(/resources/public/dtc/fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format('svg');
}

* {
   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   box-sizing: border-box;
}

:after,:before {
   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   box-sizing: border-box;
}

button {
   font-family: inherit;
   font-size: inherit;
   line-height: inherit;
}

a {
   color: #337ab7;
   text-decoration: none;
}

a:focus,a:hover {
   color: #23527c;
   text-decoration: underline;
}

a:focus {
   outline: 5px auto -webkit-focus-ring-color;
   outline-offset: -2px;
}

hr {
   margin-top: 20px;
   margin-bottom: 20px;
   border: 0;
   border-top: 1px solid #eee;
}

.sr-only {
   position: absolute;
   width: 1px;
   height: 1px;
   padding: 0;
   margin: -1px;
   overflow: hidden;
   clip: rect(0,0,0,0);
   border: 0;
}

[role=button] {
   cursor: pointer;
}

.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6 {
   font-family: inherit;
   font-weight: 500;
   line-height: 1.1;
   color: inherit;
}

.h1,.h2,.h3,h1,h2,h3 {
   margin-top: 20px;
   margin-bottom: 10px;
}

.h4,.h5,.h6,h4,h5,h6 {
   margin-top: 10px;
   margin-bottom: 10px;
}

.h1,h1 {
   font-size: 36px;
}

.h2,h2 {
   font-size: 30px;
}

.h3,h3 {
   font-size: 24px;
}

.h4,h4 {
   font-size: 18px;
}

.h5,h5 {
   font-size: 14px;
}

.h6,h6 {
   font-size: 12px;
}

p {
   margin: 0 0 10px;
}

.container {
   padding-right: 15px;
   padding-left: 15px;
   margin-right: auto;
   margin-left: auto;
}

@media (min-width:768px) {
   .container {
      width: 750px;
   }
}

@media (min-width:992px) {
   .container {
      width: 970px;
   }
}

@media (min-width: 1400px) {
   .container {
      width: calc(1400px - (2 * 15px));
   }
}

.row {
   margin-right: -15px;
   margin-left: -15px;
}

.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9 {
   position: relative;
   min-height: 1px;
   padding-right: 15px;
   padding-left: 15px;
}

@media (min-width:992px) {
   .col-md-1,  .col-md-10,  .col-md-11,  .col-md-12,  .col-md-2,  .col-md-3,  .col-md-4,  .col-md-5,  .col-md-6,  .col-md-7,  .col-md-8,  .col-md-9 {
      float: left;
   }

   .col-md-12 {
      width: 100%;
   }

   .col-md-11 {
      width: 91.66666667%;
   }

   .col-md-10 {
      width: 83.33333333%;
   }

   .col-md-9 {
      width: 75%;
   }

   .col-md-8 {
      width: 66.66666667%;
   }

   .col-md-7 {
      width: 58.33333333%;
   }

   .col-md-6 {
      width: 50%;
   }

   .col-md-5 {
      width: 41.66666667%;
   }

   .col-md-4 {
      width: 33.33333333%;
   }

   .col-md-3 {
      width: 25%;
   }

   .col-md-2 {
      width: 16.66666667%;
   }

   .col-md-1 {
      width: 8.33333333%;
   }
}

@media (min-width: 1400px) {
   .col-lg-1,  .col-lg-10,  .col-lg-11,  .col-lg-12,  .col-lg-2,  .col-lg-3,  .col-lg-4,  .col-lg-5,  .col-lg-6,  .col-lg-7,  .col-lg-8,  .col-lg-9 {
      float: left;
   }

   .col-lg-12 {
      width: 100%;
   }

   .col-lg-11 {
      width: 91.66666667%;
   }

   .col-lg-10 {
      width: 83.33333333%;
   }

   .col-lg-9 {
      width: 75%;
   }

   .col-lg-8 {
      width: 66.66666667%;
   }

   .col-lg-7 {
      width: 58.33333333%;
   }

   .col-lg-6 {
      width: 50%;
   }

   .col-lg-5 {
      width: 41.66666667%;
   }

   .col-lg-4 {
      width: 33.33333333%;
   }

   .col-lg-3 {
      width: 25%;
   }

   .col-lg-2 {
      width: 16.66666667%;
   }

   .col-lg-1 {
      width: 8.33333333%;
   }
}

.btn {
   display: inline-block;
   padding: 6px 12px;
   margin-bottom: 0;
   font-size: 14px;
   font-weight: 400;
   line-height: 1.42857143;
   text-align: center;
   white-space: nowrap;
   vertical-align: middle;
   -ms-touch-action: manipulation;
   touch-action: manipulation;
   cursor: pointer;
   -webkit-user-select: none;
   -moz-user-select: none;
   -ms-user-select: none;
   user-select: none;
   background-image: none;
   border: 1px solid transparent;
   border-radius: 4px;
}

.btn:active.focus,.btn:active:focus,.btn:focus {
   outline: 5px auto -webkit-focus-ring-color;
   outline-offset: -2px;
}

.btn:focus,.btn:hover {
   color: #333;
   text-decoration: none;
}

.btn:active {
   background-image: none;
   outline: 0;
   -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
   box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
}

.btn[disabled] {
   cursor: not-allowed;
   filter: alpha(opacity=65);
   -webkit-box-shadow: none;
   box-shadow: none;
   opacity: .65;
}

.btn-default {
   color: #333;
   background-color: #fff;
   border-color: #ccc;
}

.btn-default:focus {
   color: #333;
   background-color: #e6e6e6;
   border-color: #8c8c8c;
}

.btn-default:hover {
   color: #333;
   background-color: #e6e6e6;
   border-color: #adadad;
}

.btn-default:active {
   color: #333;
   background-color: #e6e6e6;
   border-color: #adadad;
}

.btn-default:active.focus,.btn-default:active:focus,.btn-default:active:hover {
   color: #333;
   background-color: #d4d4d4;
   border-color: #8c8c8c;
}

.btn-default:active {
   background-image: none;
}

.btn-default[disabled]:focus,.btn-default[disabled]:hover {
   background-color: #fff;
   border-color: #ccc;
}

.btn-primary {
   color: #fff;
   background-color: #337ab7;
   border-color: #2e6da4;
}

.btn-primary:focus {
   color: #fff;
   background-color: #286090;
   border-color: #122b40;
}

.btn-primary:hover {
   color: #fff;
   background-color: #286090;
   border-color: #204d74;
}

.btn-primary:active {
   color: #fff;
   background-color: #286090;
   border-color: #204d74;
}

.btn-primary:active.focus,.btn-primary:active:focus,.btn-primary:active:hover {
   color: #fff;
   background-color: #204d74;
   border-color: #122b40;
}

.btn-primary:active {
   background-image: none;
}

.btn-primary[disabled]:focus,.btn-primary[disabled]:hover {
   background-color: #337ab7;
   border-color: #2e6da4;
}

.btn-link {
   font-weight: 400;
   color: #337ab7;
   border-radius: 0;
}

.btn-link,.btn-link:active,.btn-link[disabled] {
   background-color: transparent;
   -webkit-box-shadow: none;
   box-shadow: none;
}

.btn-link,.btn-link:active,.btn-link:focus,.btn-link:hover {
   border-color: transparent;
}

.btn-link:focus,.btn-link:hover {
   color: #23527c;
   text-decoration: underline;
   background-color: transparent;
}

.btn-link[disabled]:focus,.btn-link[disabled]:hover {
   color: #777;
   text-decoration: none;
}

.btn-lg {
   padding: 10px 16px;
   font-size: 18px;
   line-height: 1.3333333;
   border-radius: 6px;
}

.collapse {
   display: none;
}

.nav {
   padding-left: 0;
   margin-bottom: 0;
   list-style: none;
}

.navbar {
   position: relative;
   min-height: 50px;
   margin-bottom: 20px;
   border: 1px solid transparent;
}

@media (min-width:768px) {
   .navbar {
      border-radius: 4px;
   }
}

@media (min-width:768px) {
   .navbar-header {
      float: left;
   }
}

.navbar-collapse {
   padding-right: 15px;
   padding-left: 15px;
   overflow-x: visible;
   -webkit-overflow-scrolling: touch;
   border-top: 1px solid transparent;
   -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.1);
   box-shadow: inset 0 1px 0 rgba(255,255,255,.1);
}

@media (min-width:768px) {
   .navbar-collapse {
      width: auto;
      border-top: 0;
      -webkit-box-shadow: none;
      box-shadow: none;
   }

   .navbar-collapse.collapse {
      display: block;
      height: auto;
      padding-bottom: 0;
      overflow: visible;
   }

   .navbar-fixed-top .navbar-collapse {
      padding-right: 0;
      padding-left: 0;
   }
}

.navbar-fixed-top .navbar-collapse {
   max-height: 340px;
}

@media (max-device-width:480px) and (orientation:landscape) {
   .navbar-fixed-top .navbar-collapse {
      max-height: 200px;
   }
}

.container>.navbar-collapse,.container>.navbar-header {
   margin-right: -15px;
   margin-left: -15px;
}

@media (min-width:768px) {
   .container>.navbar-collapse,  .container>.navbar-header {
      margin-right: 0;
      margin-left: 0;
   }
}

.navbar-fixed-top {
   position: fixed;
   right: 0;
   left: 0;
   z-index: 1030;
}

@media (min-width:768px) {
   .navbar-fixed-top {
      border-radius: 0;
   }
}

.navbar-fixed-top {
   top: 0;
   border-width: 0 0 1px;
}

.navbar-brand {
   float: left;
   height: 50px;
   padding: 15px 15px;
   font-size: 18px;
   line-height: 20px;
}

.navbar-brand:focus,.navbar-brand:hover {
   text-decoration: none;
}

@media (min-width:768px) {
   .navbar>.container .navbar-brand {
      margin-left: -15px;
   }
}

.navbar-toggle {
   position: relative;
   float: right;
   padding: 9px 10px;
   margin-top: 8px;
   margin-right: 15px;
   margin-bottom: 8px;
   background-color: transparent;
   background-image: none;
   border: 1px solid transparent;
   border-radius: 4px;
}

.navbar-toggle:focus {
   outline: 0;
}

.navbar-toggle .icon-bar {
   display: block;
   width: 22px;
   height: 2px;
   border-radius: 1px;
}

.navbar-toggle .icon-bar+.icon-bar {
   margin-top: 4px;
}

@media (min-width:768px) {
   .navbar-toggle {
      display: none;
   }
}

.navbar-nav {
   margin: 7.5px -15px;
}

@media (min-width:768px) {
   .navbar-nav {
      float: left;
      margin: 0;
   }
}

.navbar-btn {
   margin-top: 8px;
   margin-bottom: 8px;
}

.navbar-default {
   background-color: #f8f8f8;
   border-color: #e7e7e7;
}

.navbar-default .navbar-brand {
   color: #777;
}

.navbar-default .navbar-brand:focus,.navbar-default .navbar-brand:hover {
   color: #5e5e5e;
   background-color: transparent;
}

.navbar-default .navbar-toggle {
   border-color: #ddd;
}

.navbar-default .navbar-toggle:focus,.navbar-default .navbar-toggle:hover {
   background-color: #ddd;
}

.navbar-default .navbar-toggle .icon-bar {
   background-color: #888;
}

.navbar-default .navbar-collapse {
   border-color: #e7e7e7;
}

.navbar-default .navbar-link {
   color: #777;
}

.navbar-default .navbar-link:hover {
   color: #333;
}

.navbar-default .btn-link {
   color: #777;
}

.navbar-default .btn-link:focus,.navbar-default .btn-link:hover {
   color: #333;
}

.navbar-default .btn-link[disabled]:focus,.navbar-default .btn-link[disabled]:hover {
   color: #ccc;
}

.navbar-inverse {
   background-color: #222;
   border-color: #080808;
}

.navbar-inverse .navbar-brand {
   color: #9d9d9d;
}

.navbar-inverse .navbar-brand:focus,.navbar-inverse .navbar-brand:hover {
   color: #fff;
   background-color: transparent;
}

.navbar-inverse .navbar-toggle {
   border-color: #333;
}

.navbar-inverse .navbar-toggle:focus,.navbar-inverse .navbar-toggle:hover {
   background-color: #333;
}

.navbar-inverse .navbar-toggle .icon-bar {
   background-color: #fff;
}

.navbar-inverse .navbar-collapse {
   border-color: #101010;
}

.navbar-inverse .navbar-link {
   color: #9d9d9d;
}

.navbar-inverse .navbar-link:hover {
   color: #fff;
}

.navbar-inverse .btn-link {
   color: #9d9d9d;
}

.navbar-inverse .btn-link:focus,.navbar-inverse .btn-link:hover {
   color: #fff;
}

.navbar-inverse .btn-link[disabled]:focus,.navbar-inverse .btn-link[disabled]:hover {
   color: #444;
}

.jumbotron {
   padding-top: 30px;
   padding-bottom: 30px;
   margin-bottom: 30px;
   color: inherit;
   background-color: #eee;
}

.jumbotron .h1,.jumbotron h1 {
   color: inherit;
}

.jumbotron p {
   margin-bottom: 15px;
   font-size: 21px;
   font-weight: 200;
}

.jumbotron>hr {
   border-top-color: #d5d5d5;
}

.container .jumbotron {
   padding-right: 15px;
   padding-left: 15px;
   border-radius: 6px;
}

.jumbotron .container {
   max-width: 100%;
}

@media screen and (min-width:768px) {
   .jumbotron {
      padding-top: 48px;
      padding-bottom: 48px;
   }

   .container .jumbotron {
      padding-right: 60px;
      padding-left: 60px;
   }

   .jumbotron .h1,  .jumbotron h1 {
      font-size: 63px;
   }
}

@-webkit-keyframes progress-bar-stripes {
   from {
      background-position: 40px 0;
   }

   to {
      background-position: 0 0;
   }
}

@-o-keyframes progress-bar-stripes {
   from {
      background-position: 40px 0;
   }

   to {
      background-position: 0 0;
   }
}

@keyframes progress-bar-stripes {
   from {
      background-position: 40px 0;
   }

   to {
      background-position: 0 0;
   }
}

.container:after,.container:before,.nav:after,.nav:before,.navbar-collapse:after,.navbar-collapse:before,.navbar-header:after,.navbar-header:before,.navbar:after,.navbar:before,.row:after,.row:before {
   display: table;
   content: " ";
}

.container:after,.nav:after,.navbar-collapse:after,.navbar-header:after,.navbar:after,.row:after {
   clear: both;
}

.hidden {
   display: none;
}

.visible-lg,.visible-md {
   display: none;
}

@media (min-width:992px) and (max-width:1199px) {
   .visible-md {
      display: block;
   }
}

@media (min-width: 1400px) {
   .visible-lg {
      display: block;
   }
}

@media (min-width:992px) and (max-width:1199px) {
   .hidden-md {
      display: none;
   }
}

@media (min-width: 1400px) {
   .hidden-lg {
      display: none;
   }
}

body {
   margin: 0;
   font-family: 'Open Sans', sans-serif;
   font-weight: 400;
   font-size: 15px;
}

.building {
   background: url("clouds.png") no-repeat;
   background-attachment: fixed;
   -webkit-background-size: cover;
   background-size: cover;
   overflow: hidden;
   text-align: center;
}

.building #l-wrapper {
   text-align: center;
}

.building .building-logo {
   margin-top: 60px;
   max-width: 250px;
}

.building .top-title {
   margin-top: 80px;
   color: #11ace4;
   font-weight: 800;
   transition: font-size ease-in .3s;
}

.building .url {
   margin-top: 20px;
   display: block;
   font-size: 60px;
   font-weight: 600;
   color: #1d1d1b;
   word-break: break-all;
   -webkit-transition: color ease-in-out 0.3s;
   -o-transition: color ease-in-out 0.3s;
   transition: color ease-in-out 0.3s, font-size ease-in .3s;
}

.building .description {
   margin-top: 60px;
   margin-left: auto;
   margin-right: auto;
   font-weight: 600;
   line-height: 30px;
   padding-left: 30vh;
   padding-right: 30vh;
}

.building .tel {
   margin-top: 35px;
   display: inline-block;
   color: #000;
   font-weight: 800;
   -webkit-transition: color ease-in-out 0.3s;
   -o-transition: color ease-in-out 0.3s;
   transition: color ease-in-out 0.3s;
   position: relative;
}

.building .phone {
   width: 20px;
   position: absolute;
   left: -40px;
}

.building .tel:hover {
   color: #11ace4;
}

.building footer {
   position: fixed;
   bottom: 0;
   width: 100%;
   background-color: #fff;
   height: 115px;
   display: block;
}

.building footer .menu {
   padding: 0;
   background-color: #fff;
   display: -webkit-box;
   display: -webkit-flex;
   display: -ms-flexbox;
   display: flex;
   -webkit-box-pack: center;
   -webkit-justify-content: center;
   -ms-flex-pack: center;
   justify-content: center;
   transition: transform linear .3s;
   height: 78vh;
}

.building footer .menu li {
   float: left;
   list-style: none;
   padding-top: 35px;
   padding-bottom: 25px;
}

.building footer .menu li + li {
   margin-left: 50px;
}

.building footer .menu li span {
   color: #000;
   font-weight: 600;
   -webkit-transition: color ease-in-out 0.3s;
   -o-transition: color ease-in-out 0.3s;
   transition: color ease-in-out 0.3s;
}

.building footer .menu li:hover > a > span {
   color: #11ace4;
}

.btn {
   height: 50px;
   background-color: #ff0054;
   color: #fff;
   line-height: 50px;
   text-transform: uppercase;
   padding: 0 15px;
   margin-top: 30px;
   margin-left: auto;
   margin-right: auto;
   margin-bottom: 0;
   display: block;
   text-align: center;
   vertical-align: middle;
   -ms-touch-action: manipulation;
   touch-action: manipulation;
   cursor: pointer;
   background-image: none;
   border: 1px solid #ff0054;
   white-space: nowrap;
   font-size: 14px;
   border-radius: 4px;
   -webkit-user-select: none;
   -moz-user-select: none;
   -ms-user-select: none;
   user-select: none;
   font-weight: 600;
   max-width: 325px;
   transition: background-color ease-out .3s;
}

.btn:hover {
   background-color: #fc3d7c;
   color: #fff;
}

a {
   text-decoration: none;
}

.description a{
   text-decoration: underline;
}

.mobile {
   display: none;
   background-color: #fff;
   transition: transform linear .3s;
}

.building footer .menu-wrap.active {
   transform: translate(0, -75vh);
}

.menu-wrap {
   background-color: #fff;
   transition: transform linear .3s;
}

@media (max-width: 1399px) {
   .building .description {
      padding-left: 5vw;
      padding-right: 5vw;
   }
}

@media (max-width: 999px) {
   .menu {
      margin: 0;
      flex-direction: column;
   }

   .building {
      overflow: auto;
      margin-bottom: 150px;
   }

   .mobile {
      display: block;
      margin-left: auto;
      margin-right: auto;
      height: 100%;
      padding-top: 25px;
   }

   .building footer .menu li{
      padding: 20px;
   }

   .building footer .menu li + li {
      margin-left: 0;
   }

   .building footer {
      height: 12vh;
   }

   .building .description {
      padding-left: 1vw;
      padding-right: 1vw;
   }
}

@media(max-width: 620px){
   .building .url {
      font-size: 44px;
   }

   .building .top-title,
   .building .description {
      font-size: 14px;
   }

   .building .description {
      margin-top: 30px;
   }
}

@media(max-width: 470px){
   .building .url {
      font-size: 30px;
   }

   .building .top-title,
   .building .description {
      font-size: 12px;
   }

   .building .description {
      line-height: 20px;
   }

   .building .top-title {
      margin-top: 20px;
   }
}   
   </style>

</head>

<?php
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if ($lang<>"de" AND $lang<>"fr")
		$lang="de";
	$headTitle['de']='Webhosting Anbieter: Hosting, Server, Domains - kreativmedia.ch';
	$headDescription['de']='Ihr Ansprechpartner für klassisches Webhosting, Reseller Hosting für Wiederverkäufer und Agenturen und Domains.';
	$topTitle['de']='Hier entsteht in Kürze die Webseite von';
	$description['de']='KreativMedia ist Ihr Ansprechpartner für <a href="https://www.kreativmedia.ch/" target="_blank" title="klassisches Webhosting">klassisches Webhosting</a>, Domains und Reseller Hosting
      für Wiederverkäufer und Agenturen. Wir bieten auch professionelle E-Mail Lösungen für Ihr Unternehmen,
      idealen Schutz Ihrer Webseiten durch SSL-Verschlüsselung sowie massgeschneiderte Server.';
	$btn['de']='Starten Sie jetzt Ihr Projekt';
	$url['webhosting']['de']='<li><a href="https://www.kreativmedia.ch/de/webhosting/hosting"><span>Webhosting</span></a></li>';
	$url['server']['de']='<li><a href="https://www.kreativmedia.ch/de/server/root-managed-cloud-server"><span>Server</span></a></li>';
	$url['domains']['de']='<li><a href="https://www.kreativmedia.ch/de/domains/domain-registrieren-transferieren"><span>Domains</span></a></li>';
	$url['reseller']['de']='<li><a href="https://www.kreativmedia.ch/de/reseller-agenturen/reseller-hosting"><span>Reseller & Agenturen</span></a></li>';
	$url['email']['de']='<li><a href="https://www.kreativmedia.ch/de/email/business-email"><span>E-Mail</span></a></li>';
	$url['ssl']['de']='<li><a href="https://www.kreativmedia.ch/de/ssl-zertifikate/ssl-zertifikate"><span>SSL-Zertifikate</span></a></li>';
	  
	$headTitle['fr']='Hébergement Web Suisse, Serveur, Noms de domaine : sûr et avantageux - kreativmedia.ch';
	$headDescription['fr']='Votre interlocuteur pour les hébergements classiques, les hébergements pour les revendeurs, agences et domaines.';
	$topTitle['fr']='Ce site est en construction.';
	$description['fr']='KreativMedia est votre interlocuteur pour tout ce qui concerne <a href="https://www.kreativmedia.ch/" target="_blank" title="l’hébergement Web">l’hébergement Web</a>, les noms de domaine et l’hébergement Revendeur pour les revendeurs et les agences. Nous proposons également des solutions de messagerie professionnelles pour votre entreprise, une protection idéale de vos sites Web par chiffrement SSL et des serveurs sur mesure.';
	$btn['fr']='Démarrez votre projet maintenant';
	$url['webhosting']['fr']='<li><a href="https://www.kreativmedia.ch/fr/hebergement-web/hebergement"><span>Hébergement Web</span></a></li>';
	$url['server']['fr']='<li><a href="https://www.kreativmedia.ch/fr/serveur/root-manage-serveur-cloud"><span>Serveur</span></a></li>';
	$url['domains']['fr']='<li><a href="https://www.kreativmedia.ch/fr/domaines/enregistrer-transferer-domaines"><span>Noms de domaine</span></a></li>';
	$url['reseller']['fr']='<li><a href="https://www.kreativmedia.ch/fr/revendeurs-agences/webbuilderkit-reseller"><span>Revendeurs & Agences</span></a></li>';
	$url['email']['fr']='<li><a href="https://www.kreativmedia.ch/fr/email/business-email"><span>E-mail</span></a></li>';
	$url['ssl']['fr']='<li><a href="https://www.kreativmedia.ch/fr/certificats-ssl/certificats-ssl"><span>Certificats SSL</span></a></li>';	
?>

<body class="building">

<div class="container">
   <a href="https://www.kreativmedia.ch" title="Webhosting" target="_blank">
      <img src="logo_kreativ.png" alt="kreativmedia logo" class="building-logo">
   </a>
   <div class="top-title"><?php echo $topTitle[$lang]?></div>
   <div class="url">jeux.conod.org</div>
   <p class="description"><?php echo $description[$lang]?></p>

   <div class="col-md-12">
      <a href="https://www.kreativmedia.ch" class="btn"><?php echo $btn[$lang]?></a>
   </div>

</div>
<footer>
      <div class="menu-wrap">
         <svg class="mobile" onclick="openMenu()" height="32px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M4,10h24c1.104,0,2-0.896,2-2s-0.896-2-2-2H4C2.896,6,2,6.896,2,8S2.896,10,4,10z M28,14H4c-1.104,0-2,0.896-2,2  s0.896,2,2,2h24c1.104,0,2-0.896,2-2S29.104,14,28,14z M28,22H4c-1.104,0-2,0.896-2,2s0.896,2,2,2h24c1.104,0,2-0.896,2-2  S29.104,22,28,22z"/></svg>

         <ul class="menu">
            <?php echo $url['webhosting'][$lang]?>
            <?php echo $url['server'][$lang]?>
            <?php echo $url['domains'][$lang]?>
            <?php echo $url['reseller'][$lang]?>
            <?php echo $url['email'][$lang]?>
            <?php echo $url['ssl'][$lang]?>
         </ul>
      </div>

</footer>

<script>
   function openMenu(){
      var menu = document.querySelector('.menu-wrap');

      if(menu.classList.contains('active')){
         menu.classList.remove('active');
      } else {
         menu.classList.add('active');
      }
   }
</script>
</body>
</html>