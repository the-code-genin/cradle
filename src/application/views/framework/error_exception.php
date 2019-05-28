<!DOCTYPE html>
<html>
<head>
	<title><?=$throwableType?> Encountered</title>
	<meta name="viewport" content="width=device-width;initial-scale=1.0" />
	<style>
		body {
			background-color: #f4f4f4;
			font-family: Helvetica, Arial, Sans-serif;
		}

		.box {
			padding: 15px;
			padding-top: 5px;
			margin-top: 5%;
			margin-bottom: 5%;
			border-radius: 2px;
			box-shadow: 0 3px 20px 0px rgba(0, 0, 0, 0.12);
		    -moz-box-shadow: 0 3px 20px 0px rgba(0, 0, 0, 0.12);
		    -webkit-box-shadow: 0 3px 20px 0px rgba(0, 0, 0, 0.12);
		    -o-box-shadow: 0 3px 20px 0px rgba(0, 0, 0, 0.12);
		    -ms-box-shadow: 0 3px 20px 0px rgba(0, 0, 0, 0.12);
		    background-color: #fbfbfb;
		}

		@media (min-width: 576px) {
			.box {
				margin-left: 20%;
				margin-right: 20%;
			}
		}

		.divider {
			height: 1px;
			overflow: hidden;
			border-bottom: 1px solid #ddd;
		}

		h1 {
			color: #424242;
			margin-top: 10px;
			margin-bottom: 10px;
			font-weight: 600;
			font-size: 2.15em;
		}

		p, li {
			color: #464646;
			padding: 2px;
			margin-top: 4px;
			margin-bottom: 4px;
			font-size: 1.15em;
			font-weight: 400;
			line-height: 1.3em;
		}

		ul {
			list-style-type: none;
		}
	</style>
</head>
<body>

	<div class="box">

		<h1>An <?=$throwableType?> was encountered</h1>

		<div class="divider"></div>

		<p><b>Type:</b> <?=$throwableClass?></p>
		<p><b>Message:</b> <?=$throwableMessage?></p>
		<p><b>Code:</b> <?=$throwableCode?></p>
		<p><b>File:</b> <?=$throwableFile?></p>
		<p><b>Line:</b> <?=$throwableLine?></p>
		<p><b>BackTrace:</b>
			<ul>
				<?php foreach ($throwableTrace as $trace): ?>
					<li>
						<?php
							if (!empty($trace['file']) & !empty($trace['line'])) {
								printf("%s%s%s(%s) in file <b>%s</b> on <b>line %s</b>",
									@$trace['class'],
									@$trace['type'],
									@$trace['function'],
									@implode(', ', $trace['args']),
									$trace['file'],
									$trace['line'],
								);
							}
						?>
					</li>
				<?php endforeach;?>
			</ul>
		</p>
	</div>

</body>
</html>