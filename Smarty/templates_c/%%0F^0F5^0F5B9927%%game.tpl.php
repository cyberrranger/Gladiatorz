<?php /* Smarty version 2.6.14, created on 2011-11-28 11:26:34
         compiled from game.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="Content-Language" content="de" />
	<meta name="author" content="Patrick Schön http://www.cyberrranger.de" />
	<meta name="description" content="Gladiatorgame.de - Gladiatorz das Browsergame mit den antiken Recken der Arena!">
	<meta name="ROBOTS" content="index, follow" />
	<meta name="keywords" content="Gladiatorz, Gladiatorgame.de, Browsergame, Game, Arena, Spiel, Antike, Fun">
	<meta name="date" content="<?php echo $this->_tpl_vars['MetaDate']; ?>
">

	<title>Gladiatorgame.de - Gladiatorz das Browsergame mit den antiken Recken der Arena!</title>

	<link rel="shortcut icon" href="favicon.ico" type="image/ico" />
	<link href="Templates/game.css" rel="stylesheet" type="text/css" />

	<script language="JavaScript" type="text/javascript" src="System/JavaScript.js"></script>

	<link type="text/css" href="Templates/ui/css/sunny/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="Templates/ui/js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="Templates/ui/js/jquery-ui-1.8.13.custom.min.js"></script>

	<script src="Templates/toolTip.js" language="javascript" type="text/javascript"></script>
	<link href="Templates/toolTip.css" rel="Stylesheet" type="text/css" media="screen" />

	<?php echo '
	<script type=\'text/javascript\'>
		$(function() {
			$("button").button();
		});

		 $(document).ready(function() {
			    $(\'#dialog_level\').dialog({
			    	bgiframe: true,
			    	autoOpen: false,
			    	height: 150,
			    	minHeight: 150,
			    	modal: true
			    });
			    $(\'#dialog_level\').dialog(\'open\');

			  	$(\'#dialog_rang\').dialog({
			    	bgiframe: false,
			    	autoOpen: false,
			    	height: 250,
			    	minHeight: 250,
			    	modal: true
			    });

			    $(\'#dialog_rang\').dialog(\'open\');

				$("#showok").click(function() {
					var newmsg = $("#boxmsg").val();
					var result = actionRequest(\'action/sb/reload.php\', \'msg=\'+newmsg+\'&user=';  echo $this->_tpl_vars['username'];  echo '\')
					if (result)
					{
						$("#boxmsg").val(\'\');
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
									var result = actionRequest("action/msg/new.php", \'do=new&to=\'+to+\'&title=\'+title+\'&msg=\'+msg)
									if (result.errorcode) {
										alert(result.msg)
									} else {
										alert(result.msg)
										$(this).dialog(\'close\');
									}
								}
							},
							Abbrechen: function() {
								$(this).dialog(\'close\');
							}
						}
					});
			  });

		</script>

	'; ?>

	<script type="text/javascript" src="Templates/function.js"></script>

	<script type='text/javascript'>
			 window.setInterval("reloadSB()", 10000);
	</script>

</head>

<body id="body">

<div id="pm" title="PowMod" <?php echo $this->_tpl_vars['TT_PowMod']; ?>
><?php echo $this->_tpl_vars['User']['powmod']; ?>
</div>
<div id="hp" title="Kraft" <?php echo $this->_tpl_vars['TT_Kraft']; ?>
><?php echo $this->_tpl_vars['User']['kraft']; ?>
</div>
<div id="gold" title="Gold" <?php echo $this->_tpl_vars['TT_Gold']; ?>
><?php echo $this->_tpl_vars['User']['gold']; ?>
</div>
<div id="online" title="Spieler online" <?php echo $this->_tpl_vars['TT_Spieler_online']; ?>
><?php echo $this->_tpl_vars['CPlayer']; ?>
 Spieler online</div>
<div id="healer" title="Heilerbutton" <?php echo $this->_tpl_vars['TT_Heilerbutton']; ?>
 onclick="HealerButton();"></div>

<a id="logout" href="index.php?site=logout" target="_self" title="Logout" <?php echo $this->_tpl_vars['TT_Logout']; ?>
></a>
<a id="post_out" href="index.php?site=postausgang" target="_self" title="Postausgang" <?php echo $this->_tpl_vars['TT_Postausgang']; ?>
></a>

<?php if ($this->_tpl_vars['Msg'] >= 1 && $this->_tpl_vars['Site'] != 'posteingang'): ?>
    <a id="post_in_alarm" href="index.php?site=posteingang" target="_self" title="Posteingang" <?php echo $this->_tpl_vars['TT_Posteingang']; ?>
></a>
<?php else: ?>
    <a id="post_in" href="index.php?site=posteingang" target="_self" title="Posteingang" <?php echo $this->_tpl_vars['TT_Posteingang']; ?>
></a>
<?php endif; ?>

<a id="post_in" href="index.php?site=posteingang" target="_self" title="Posteingang" <?php echo $this->_tpl_vars['TT_Posteingang']; ?>
></a>
<a id="post_new" href="index.php?site=nachrichten" target="_self" title="Neue Nachricht" <?php echo $this->_tpl_vars['TT_Neue_Nachricht']; ?>
></a>

<div id="show_pm" title="PowMod Anzeige" <?php echo $this->_tpl_vars['TT_PowMod_Anzeige']; ?>
></div>
<div id="show_hp" title="Kraft Anzeige" <?php echo $this->_tpl_vars['TT_Kraft_Anzeige']; ?>
></div>

<div id="exp_bar" title="EXP Anzeige"></div>
<div id="exp_full" title="EXP Leiste" <?php echo $this->_tpl_vars['TT_EXP_Anzeige']; ?>
></div>
<div id="exp_symbol" title="EXP Leiste" <?php echo $this->_tpl_vars['TT_EXP_Fortschritt']; ?>
></div>
<div id="level" title="Level Anzeige" <?php echo $this->_tpl_vars['TT_Level_Anzeige']; ?>
><?php echo $this->_tpl_vars['User']['level']; ?>
</div>

<div id="main">

<div id="left_nav">
	<div id="nav_top"></div>
	<div id="nav_side">
		<a href="index.php?site=uebersicht" <?php echo $this->_tpl_vars['TT_Uebersicht']; ?>
>&Uuml;bersicht</a>
		<a href="index.php?site=charakter" <?php echo $this->_tpl_vars['TT_Charakter']; ?>
>Charakter</a>
		<a href="index.php?site=trainer" <?php echo $this->_tpl_vars['TT_Trainer']; ?>
>Trainer</a>
		<a href="index.php?site=moves" >Spezial Moves</a>
		<a href="index.php?site=inventar" <?php echo $this->_tpl_vars['TT_Inventar']; ?>
>Inventar</a><br />
		<a href="index.php?site=tiergrube" <?php echo $this->_tpl_vars['TT_Tiergrube']; ?>
>Tiergrube</a>
		<a href="index.php?site=rang" <?php echo $this->_tpl_vars['TT_Rangkaempfe']; ?>
>Rangk&auml;mpfe</a>
		<a href="index.php?site=arena" <?php echo $this->_tpl_vars['TT_Arena']; ?>
>Arena</a>
		<a href="index.php?site=prestige" <?php echo $this->_tpl_vars['TT_Prestigekaempfe']; ?>
>Prestigek&auml;mpfe</a>
		<a href="index.php?site=challenge" <?php echo $this->_tpl_vars['TT_challenge']; ?>
>Herausforderungen</a>
		<a href="index.php?site=turnier" <?php echo $this->_tpl_vars['TT_turnier']; ?>
>Turnier</a>
		<a href="index.php?site=duelle" <?php echo $this->_tpl_vars['TT_Duelle']; ?>
>Duelle</a><br />
		<a href="index.php?site=marketplace" >Marktplatz</a>
		<a href="index.php?site=schulen" <?php echo $this->_tpl_vars['TT_Schulen']; ?>
>Schulen</a>
		<a href="index.php?site=gasthaus" <?php echo $this->_tpl_vars['TT_Gasthaus']; ?>
>Gasthaus</a>
		<a href="index.php?site=quest" <?php echo $this->_tpl_vars['TT_Arenaleitung']; ?>
>Quest</a><br />
		<a href="index.php?site=forum" <?php echo $this->_tpl_vars['TT_Forum']; ?>
>Forum</a>
		<a href="http://wiki.gladiatorgame.de" <?php echo $this->_tpl_vars['TT_Hilfe']; ?>
>Hilfe</a>
		<a href="index.php?site=search&what=player" <?php echo $this->_tpl_vars['TT_Suche']; ?>
>Suche</a>
		<a href="index.php?site=medaillen" <?php echo $this->_tpl_vars['TT_Medaillen']; ?>
>Medaillen</a>
		<a href="index.php?site=highscore" <?php echo $this->_tpl_vars['TT_Highscore']; ?>
>Highscore</a>
		<a href="index.php?site=stats" <?php echo $this->_tpl_vars['TT_Statistiken']; ?>
>Statistiken</a>
		<a href="index.php?site=hof" <?php echo $this->_tpl_vars['TT_Hall_of_Fame']; ?>
>Hall of Fame</a><br />
	</div>
	<div id="nav_footer"></div>
</div>

<div id="right_body">

  <div id="header"></div>
  <div id="leader_ad"></div>

  <div id="content_main">
  <div id="content_header"></div>

  <div id="content">