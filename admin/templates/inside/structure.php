<?php
	$config = $JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_PATH);
	$config_tree = $JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_TREE_PATH);

	$structure = $config['structure'];
	$tree = $config_tree['structure'];

	$structure_and_tree = $Core->json_and_tree_union($structure, $tree);

	$params_form = $Core->create_form($structure_and_tree);

	$inline_params = $Core->get_undefined_params($structure, $tree, unserialize(CONFIG_TREE_DEFAULT)['structure']);
	$undef_settings_count = $Core->get_undef_count($inline_params);
	$settings_undef_count = ($undef_settings_count > 0) ? "<span class='a-alert'>".$undef_settings_count."</span>" : "";
?>
<form action='' method='POST' id='structure' style='height: 100%;'>
	<div class="a-container">
			<header class="a-header a-header-page">
				<div class="a-header__right">
					<a href="#settings" id='edit_settings' class="a-btn a-btn-light i-popup"><i class="icon-settings"></i><span class="a-btn__text">Настроить поля</span> <?=$settings_undef_count;?></a>
					<a href="#add" id='add_param' class="a-btn i-popup"><i class="icon-plus"></i> <span class="a-btn__text">Добавить параметр</span></a>
					<?=$Core->input("hidden", "structure", "save_tree", "save_tree");?>
					<input type="submit" class='a-btn' id='save' name='save' value='Сохранить'>
				</div>
				<div class="a-header__head">
					<a href='#' class='a-btn a-btn-light a-header__open-nav i-nav-open'><i class="icon-menu"></i></a>
					<h1>Структура</h1>
				</div>
			</header>

			<div class="a-content">
				<?php
					print_r($params_form);
				?>
			</div>
	</div>
</form>

<? 
	$parent_tree = $config_tree["structure"]['pages'];
	$parent_prefix = "structure|pages";
	$save_prefix = "structure";
	require_once($_SERVER['DOCUMENT_ROOT'].BASE_URL."/".ADMIN_FOLDER."/templates/inside/add_field.php");
	require_once($_SERVER['DOCUMENT_ROOT'].BASE_URL."/".ADMIN_FOLDER."/templates/inside/edit_fields.php");
?>