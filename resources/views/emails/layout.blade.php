<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<?php
$style = [
	// Layout
	'body' => 'margin: 0; padding: 0; width: 100%;',
  // Masthead
	'email-masthead_name' => 'font-size: 15px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;line-height: 25px;',
	'email-masthead_title' => 'font-size: 15px; font-weight: 300; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;line-height: 25px;',
	'email-masthead_small' => 'font-size: 15px; font-weight: 200; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;line-height: 25px;'
];
$fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;';
?>

<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%" align="center">
				<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td width="600" align="center" style="padding-bottom: 40px">
								<a href="{{ url('/') }}" target="_blank">
									<img src="{{ asset('images/logo-with-name.png') }}" width="200" />
								</a>
							</td>	
						</tr>
					</thead>
					<tbody border="0">
						<tr>
							<td width="600" style="{{ $fontFamily }} {{ $style['email-masthead_small'] }}">
								@yield('content')
							</td>
						</tr>
					</tbody>
					<tfoot>						
						<tr>
							<td width="600" align="left" style="{{ $fontFamily }} {{ $style['email-masthead_small'] }}">
								<strong><b>Bizzmo</b></strong>
							</td>						
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>