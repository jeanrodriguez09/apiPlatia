<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Pagina no encontrada</title>

	<style>
		div.logo {
			height: 200px;
			width: 155px;
			display: inline-block;
			opacity: 0.08;
			position: absolute;
			top: 2rem;
			left: 50%;
			margin-left: -73px;
		}
		body {
			height: 100%;
			background: #fafafa;
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			color: #777;
			font-weight: 300;
		}
		h1 {
			font-weight: lighter;
			letter-spacing: 0.8;
			font-size: 3rem;
			margin-top: 0;
			margin-bottom: 0;
			color: #222;
		}
		.wrap {
			max-width: 1024px;
			margin: 5rem auto;
			padding: 2rem;
			background: #fff;
			text-align: center;
			border: 1px solid #efefef;
			border-radius: 0.5rem;
			position: relative;
		}
		pre {
			white-space: normal;
			margin-top: 1.5rem;
		}
		code {
			background: #fafafa;
			border: 1px solid #efefef;
			padding: 0.5rem 1rem;
			border-radius: 5px;
			display: block;
		}
		p {
			margin-top: 1.5rem;
		}
		.footer {
			margin-top: 2rem;
			border-top: 1px solid #efefef;
			padding: 1em 2em 0 2em;
			font-size: 85%;
			color: #999;
		}
		a:active,
		a:link,
		a:visited {
			color: #dd4814;
		}
		.u-body {
			color: #111111;
			background-color: #ffffff;
		}
		.u-body {
			font-family: 'Open Sans',sans-serif;
		}
		.u-body {
			font-size: 1rem;
			line-height: 1.6;
		}
		section.u-image, .u-sheet.u-image, .u-group.u-image, .u-layout-cell.u-image {
			overflow: visible;
		}
		img.u-image, .u-video-poster {
			overflow: hidden;
		}
		body {
			background-image: url(<?= base_url('/public/img/logo/fondo.jpg') ?>);
			background-position: 50% 50%;
		}
		.u-overlap.u-overlap-transparent .u-header, .u-image, .u-gradient {
			color: #111111;
		}
		.u-align-center {
			text-align: center;
		}
		.u-image, .u-background-effect-image, .u-video-poster {
			object-fit: cover;
			display: block;
			vertical-align: middle;
			background-size: cover;
			background-position: 50% 50%;
			background-repeat: no-repeat;
		}
		body, aside, .u-sidebar-block, section, header, footer {
			position: relative;
		}
		.u-sheet:not(.u-image):not(.u-video) {
			pointer-events: none;
		}
		.u-section-2 .u-sheet-1 {
			min-height: 98vh;
		}
		@media (min-width: 1200px){
			.u-sheet {
				width: 1140px;
			}
		}
		.u-sheet {
			position: relative;
			width: 1140px;
			margin: 0 auto;
		}
		.u-sheet:not(.u-image):not(.u-video) > * {
			pointer-events: auto;
			pointer-events: initial;
		}
		.u-section-2 .u-text-1 {
			background-image: none;
			font-size: 4.5rem;
			font-weight: 700;
			text-shadow: 5px 4px 0px rgba(0,0,0,0.25);
			text-transform: none;
			margin: 0 222px;
		}
		.u-font-pt-sans.u-custom-font {
			font-family: 'PT Sans', sans-serif !important;
		}
		.u-body h1, .u-body h2, .u-body h3, .u-body h4, .u-body h5, .u-body h6 {
			padding: 0;
		}
		.u-text-palette-3-dark-3, a.u-button-style.u-text-palette-3-dark-3, a.u-button-style.u-text-palette-3-dark-3[class*="u-border-"] {
			color: #333129 !important;
		}
		.u-text {
			word-wrap: break-word;
			position: relative;
		}
		.u-back-image.u-image-contain, .u-image.u-image-contain {
			object-fit: contain;
			background-size: contain;
		}
		.u-sheet:not(.u-image):not(.u-video) > * {
			pointer-events: auto;
			pointer-events: initial;
		}
		.u-section-2 .u-image-1 {
			width: 36%;
			/* height: 540px; */
			margin: 31px auto 0;
		}
		.u-clearfix:after, .u-clearfix:before {
			content: '';
			display: table;
		}
		.u-clearfix:after {
			clear: both;
		}
		.u-section-2 .u-text-2 {
			font-size: 1.25rem;
			text-transform: none;
			letter-spacing: 1px;
			font-style: normal;
			font-weight: 700;
			width: 572px;
			margin: 17px auto 0;
		}
		.u-font-montserrat.u-custom-font {
			font-family: Montserrat, sans-serif !important;
		}
	</style>
</head>
<body>
	<section class="u-align-center u-clearfix u-image u-section-2" id="carousel_c20f" data-image-width="1980" data-image-height="1980">
		<div class="u-clearfix u-sheet u-valign-middle-sm u-valign-middle-xs u-sheet-1">
			<img src="<?= base_url('/public/img/logo/404.png') ?>" alt="" class="u-expanded-width-sm u-expanded-width-xs u-image u-image-contain u-image-default u-image-1" data-image-width="700" data-image-height="749">
			<h1 class="u-align-center u-custom-font u-font-pt-sans u-text u-text-palette-3-dark-3 u-text-1">Página no encontrada</h1>
			<p class="u-custom-font u-font-montserrat u-text u-text-palette-3-dark-3 u-text-2">Parece que no se puede encontrar la página que estaba buscando.</p>
		</div>
    </section>
</body>
</html>
