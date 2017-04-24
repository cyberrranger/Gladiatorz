<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="Content-Language" content="de" />
	<meta name="author" content="Patrick Schön http://www.cyberrranger.de" />
	<meta name="description" content="Gladiatorgame.de - Gladiatorz das Browsergame mit den antiken Recken der Arena!">
	<meta name="ROBOTS" content="index, follow" />
	<meta name="keywords" content="Gladiatorz, Gladiatorgame.de, Browsergame, Game, Arena, Spiel, Antike, Fun">
	<meta name="date" content="{$MetaDate}">

	<title>Gladiatorgame.de - Gladiatorz das Browsergame mit den antiken Recken der Arena!</title>

	<link rel="shortcut icon" href="favicon.ico" type="image/ico" />
	<link href="Templates/game.css" rel="stylesheet" type="text/css" />

	<script language="JavaScript" type="text/javascript" src="System/JavaScript.js"></script>

	<link type="text/css" href="Templates/ui/css/sunny/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="Templates/ui/js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="Templates/ui/js/jquery-ui-1.8.13.custom.min.js"></script>

	<script src="Templates/toolTip.js" language="javascript" type="text/javascript"></script>
	<link href="Templates/toolTip.css" rel="Stylesheet" type="text/css" media="screen" />

	{literal}
	<script type='text/javascript'>
		$(function() {
			$("button").button();
		});

		 $(document).ready(function() {
			    $('#dialog_level').dialog({
			    	bgiframe: true,
			    	autoOpen: false,
			    	height: 150,
			    	minHeight: 150,
			    	modal: true
			    });
			    $('#dialog_level').dialog('open');

			  	$('#dialog_rang').dialog({
			    	bgiframe: false,
			    	autoOpen: false,
			    	height: 250,
			    	minHeight: 250,
			    	modal: true
			    });

			    $('#dialog_rang').dialog('open');

				$("#showok").click(function() {
					var newmsg = $("#boxmsg").val();
					var result = actionRequest('action/sb/reload.php', 'msg='+newmsg+'&user={/literal}{$username}{literal}')
					if (result)
					{
						$("#boxmsg").val('');
						$("#show").html(result.text)
					}
					return false;
				});

				    $("#dialognewmsg").dialog({
						bgiframe: true,
						autoOpen: false,
						height: 500,
						width: 450,
						modal: false,
						buttons: {
							OK: function() {
								var to = $("#_to").val();
								if (to == "") {
									alert("Bitte einen Empfänger eingeben");
								} else {
									var title = $("#_title").val();
									var msg = $("#_msg").val();
									var result = actionRequest("action/msg/new.php", 'do=new&to='+to+'&title='+title+'&msg='+msg)
									if (result.errorcode) {
										alert(result.msg)
									} else {
										alert(result.msg)
										$(this).dialog('close');
									}
								}
							},
							Abbrechen: function() {
								$(this).dialog('close');
							}
						}
					});
			  });

		</script>

	{/literal}
	<script type="text/javascript" src="Templates/function.js"></script>

	<script type='text/javascript'>
			 window.setInterval("reloadSB()", 10000);
	</script>

</head>

<body id="body">

<div id="pm" title="PowMod" {$TT_PowMod}>{$User.powmod}</div>
<div id="hp" title="Kraft" {$TT_Kraft}>{$User.kraft}</div>
<div id="gold" title="Gold" {$TT_Gold}>{$User.gold}</div>
<div id="online" title="Spieler online" {$TT_Spieler_online}>{$CPlayer} Spieler online</div>
<div id="healer" title="Heilerbutton" {$TT_Heilerbutton} onclick="HealerButton();"></div>

<a id="logout" href="index.php?site=logout" target="_self" title="Logout" {$TT_Logout}></a>
<a id="post_out" href="index.php?site=postausgang" target="_self" title="Postausgang" {$TT_Postausgang}></a>

{if $Msg ge 1 && $Site ne "posteingang"}
    <a id="post_in_alarm" href="index.php?site=posteingang" target="_self" title="Posteingang" {$TT_Posteingang}></a>
{else}
    <a id="post_in" href="index.php?site=posteingang" target="_self" title="Posteingang" {$TT_Posteingang}></a>
{/if}

<a id="post_in" href="index.php?site=posteingang" target="_self" title="Posteingang" {$TT_Posteingang}></a>
<a id="post_new" href="index.php?site=nachrichten" target="_self" title="Neue Nachricht" {$TT_Neue_Nachricht}></a>

<div id="show_pm" title="PowMod Anzeige" {$TT_PowMod_Anzeige}></div>
<div id="show_hp" title="Kraft Anzeige" {$TT_Kraft_Anzeige}></div>

<div id="exp_bar" title="EXP Anzeige"></div>
<div id="exp_full" title="EXP Leiste" {$TT_EXP_Anzeige}></div>
<div id="exp_symbol" title="EXP Leiste" {$TT_EXP_Fortschritt}></div>
<div id="level" title="Level Anzeige" {$TT_Level_Anzeige}>{$User.level}</div>

<div id="main">

<div id="left_nav">
	<div id="nav_top"></div>
	<div id="nav_side">
		<a href="index.php?site=uebersicht" {$TT_Uebersicht}>&Uuml;bersicht</a>
		<a href="index.php?site=charakter" {$TT_Charakter}>Charakter</a>
		<a href="index.php?site=trainer" {$TT_Trainer}>Trainer</a>
		<a href="index.php?site=moves" >Spezial Moves</a>
		<a href="index.php?site=inventar" {$TT_Inventar}>Inventar</a><br />
		<a href="index.php?site=tiergrube" {$TT_Tiergrube}>Tiergrube</a>
		<a href="index.php?site=rang" {$TT_Rangkaempfe}>Rangk&auml;mpfe</a>
		<a href="index.php?site=arena" {$TT_Arena}>Arena</a>
		<a href="index.php?site=prestige" {$TT_Prestigekaempfe}>Prestigek&auml;mpfe</a>
		<a href="index.php?site=challenge" {$TT_challenge}>Herausforderungen</a>
		<a href="index.php?site=turnier" {$TT_turnier}>Turnier</a>
		<a href="index.php?site=duelle" {$TT_Duelle}>Duelle</a><br />
		<a href="index.php?site=marketplace" >Marktplatz</a>
		<a href="index.php?site=schulen" {$TT_Schulen}>Schulen</a>
		<a href="index.php?site=gasthaus" {$TT_Gasthaus}>Gasthaus</a>
		<a href="index.php?site=quest" {$TT_Arenaleitung}>Quest</a><br />
		<a href="index.php?site=forum" {$TT_Forum}>Forum</a>
		<a href="http://wiki.gladiatorgame.de" {$TT_Hilfe}>Hilfe</a>
		<a href="index.php?site=search&what=player" {$TT_Suche}>Suche</a>
		<a href="index.php?site=medaillen" {$TT_Medaillen}>Medaillen</a>
		<a href="index.php?site=highscore" {$TT_Highscore}>Highscore</a>
		<a href="index.php?site=stats" {$TT_Statistiken}>Statistiken</a>
		<a href="index.php?site=hof" {$TT_Hall_of_Fame}>Hall of Fame</a><br />
	</div>
	<div id="nav_footer"></div>
</div>

<div id="right_body">

  <div id="header"></div>
  <div id="leader_ad"></div>

  <div id="content_main">
  <div id="content_header"></div>

  <div id="content">
