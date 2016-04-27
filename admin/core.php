<?php
	class Core {
		private $JSON;
		private $mailer;

		function __construct() {
			$this->JSON = new JSON();
			$this->mailer = new PHPMailer();
		}

		public function get_all_files() {
			$all_files = array();
			$all_files['templates'] = $this->get_files_name($_SERVER['DOCUMENT_ROOT'].BASE_URL.TEMPLATE_PATH, array("html", "php"));
			$all_files['css'] = $this->get_files_name($_SERVER['DOCUMENT_ROOT'].BASE_URL.CSS_PATH, array("css"));
			$all_files['js'] = $this->get_files_name($_SERVER['DOCUMENT_ROOT'].BASE_URL.JS_PATH, array("js"));
			$all_files['mail'] = $this->get_files_name($_SERVER['DOCUMENT_ROOT'].BASE_URL.MAIL_TEMPLATE_PATH, array("php"));

			return $all_files;
		}
		//$path - relative path to folder
		//$extensions - accept extensions array
		public function get_files_name($path, $extensions, $relative = false) {
			if (!file_exists($path)) {
				mkdir($path, 0777);
			}
			$files = scandir($path);
			$template_files = array();

			foreach ($files as $key => $file) {
				$file_ext = pathinfo($file, PATHINFO_EXTENSION);
				foreach ($extensions as $ext) {
					if ($ext == $file_ext) {
						if ($relative) {
							$template_files[TEMPLATE_PATH."/".$file] = $file;
						} else {
							$template_files[$path."/".$file] = $file;
						}
					}
				}
			}

			return $template_files;
		}

		public function get_file($file) {
			if (file_exists($file)) {
				return file_get_contents($file);
			} else {
				return false;
			}
		}

		public function input($type, $value, $id, $name, $require = false, $options = array(), $addClass = "", $attributes = "") {
			$html = "";
			if ($require) {
				$require = "require";
			} else {
				$require = "";
			}
			switch ($type) {
				case 'file':
					$html .= "<input type='file' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				case 'select':
					$html .= "<select id='".$id."' name='".$name."' class='a-select ".$addClass."' ".$require." ".$attributes.">";
					foreach ($options as $option => $option_value) {
						if (is_array($option_value)) {
							$html .= "<optgroup label='".$option."'>";
							foreach ($option_value as $option_key_2 => $option_value_2) {
								$selected = "";
								if ($value == $option_key_2) {
									$selected = "selected";
								}
								$html .= "<option value='".$option_key_2."' ".$selected.">".$option_value_2."</option>";
							}
							$html .= "</optgroup>";
						} else {
							$selected = "";
							if ($value == $option) {
								$selected = "selected";
							}
							$html .= "<option value='".$option."' ".$selected.">".$option_value."</option>";
						}
					}
					$html .= "</select>";
					break;
				case 'checkbox':
					$checked = "";
					if ($value == "on") {
						$checked = "checked";
					}
					$html .= "<input type='checkbox' id='".$id."' name='".$name."' class='a-checkbox ".$addClass."' value='on' ".$checked." ".$require." ".$attributes.">";
					break;
				case 'radio':
					$counter = 0;
					foreach ($options as $option => $option_value) {
						if ($counter > 0) {
							$html .= "<br>";
						}
						$checked = "";
						if ($value == $option_value) {
							$checked = "checked";
						}
						$html .= "<input type='radio' id='".$id."[".$counter."]' name='".$name."' class='a-radio ".$addClass."' value='".$option_value."' ".$checked." ".$require." ".$attributes."> <label for='".$id."[".$counter."]' class='a-label'>".$option_value."</label>";
						$counter++;
					}
					break;
				case 'textarea':
					$html .= "<textarea id='".$id."' name='".$name."' class='a-textarea ".$addClass."' ".$require." ".$attributes.">".$value."</textarea>";
					break;
				case 'email':
					$html .= "<input type='email' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				case 'tel':
					$html .= "<input type='tel' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				case 'number':
					$html .= "<input type='number' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				case 'url':
					$html .= "<input type='url' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				case 'date':
					$html .= "<input type='date' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' autocomplete='off' ".$require." ".$attributes.">";
					break;
				case 'time':
					$html .= "<input type='time' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' autocomplete='off' ".$require." ".$attributes.">";
					break;
				case 'datetime':
					$html .= "<input type='datetime-local' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' autocomplete='off' ".$require." ".$attributes.">";
					break;
				case 'color':
					$html .= "<input type='color' id='".$id."' name='".$name."' class='a-color ".$addClass."' value='".$value."' autocomplete='off' ".$require." ".$attributes.">";
					break;
				case 'wysiwyg':
					$html .= "<textarea id='".$id."' name='".$name."' class='a-textarea a-wysiwyg ".$addClass."' ".$require." ".$attributes.">".$value."</textarea>";
					break;
				case 'hidden':
					$html .= "<input type='hidden' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				case 'password':
					$html .= "<input type='password' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
				
				default:
					$html .= "<input type='text' id='".$id."' name='".$name."' class='a-input ".$addClass."' value='".$value."' ".$require." ".$attributes.">";
					break;
			}
			return $html;
		}

		public function file_upload($file, $folder = FILEMANAGER_PATH, $rand = false) {
			$storeFolder = "";

			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

			if ($folder != '') {
				$storeFolder .= $folder;
			}
			
		    $tempFile = $file['tmp_name'];
		    $targetPath = $_SERVER['DOCUMENT_ROOT'].BASE_URL.DIRECTORY_SEPARATOR.$storeFolder.DIRECTORY_SEPARATOR;
		    if (!file_exists($targetPath)) {
		    	mkdir($targetPath);
		    }
		    $filename = pathinfo($file['name'], PATHINFO_FILENAME);
		    if ($rand) {
		    	$filename = $this->randomize_name();
		    }
		    $targetFile =  $targetPath.$filename.".".$ext;
		    move_uploaded_file($tempFile, $targetFile);

		    return $targetFile;
		}

		public function randomize_name() {
			return substr(md5(time()), rand(0, 16), 16);
		}

		public function get_possible_parrents($json, $prefix = "") {
			$array = array();

			$array[$prefix] = "Без родителя";

			$json_children = (isset($json[$prefix])) ? $json[$prefix] : $json;
			$json_children = (isset($json_children["children"])) ? $json_children["children"] : $json_children;

			$parents = $this->JSON->find_elements_with_key($json_children, "children", $prefix);
			$parents_array = array();

			foreach ($parents as $parent) {
				$parents_array[$parent] = implode(" > ", explode("|", $parent));
			}

			$array = array_merge($array, $parents_array);

			return $array;
		}

		public function json_and_tree_union($json, $tree) {
			$newtree = array();

			if (count($tree) > 0) {
				foreach ($tree as $tree_key => $tree_value) {
					if ($this->JSON->json_key_exist($tree_value, "name") && $this->JSON->json_key_exist($tree_value, "type") && $this->JSON->json_key_exist($tree_value, "desc")) {
						$newtree[$tree_key]['name'] = $tree_value['name'];
						$newtree[$tree_key]['type'] = $tree_value['type'];
						$newtree[$tree_key]['desc'] = $tree_value['desc'];

						if ($this->JSON->json_key_exist($tree_value, "children")) {
							if ($this->JSON->json_key_exist($tree_value, "tab_header")) {
								$newtree[$tree_key]['tab_header'] = $tree_value['tab_header'];
							}
							if ($this->JSON->json_key_exist($json, $tree_key)) {
								if (is_array($json[$tree_key])) {
									foreach ($json[$tree_key] as $json_value) {
										$newtree[$tree_key]['children'][] = $this->json_and_tree_union($json_value, $tree_value['children']);
									}
									$newtree[$tree_key]['children']["%n%"] = $this->json_and_tree_union(array(), $tree_value['children']);
								} else {
									$newtree[$tree_key]['children'][] = $this->config_and_tree_union($json[$tree_key], $tree_value['children']);
								}
							} else {
								$newtree[$tree_key]['children']["%n%"] = $this->json_and_tree_union(array(), $tree_value['children']);
							}
						} else if ($this->JSON->json_key_exist($tree_value, "children_type")) {
							$newtree[$tree_key]['children_type'] = $tree_value['children_type'];
							
							if ($this->JSON->json_key_exist($json, $tree_key)) {
								if (is_array($json[$tree_key])) {
									foreach ($json[$tree_key] as $json_value) {
										$newtree[$tree_key]['value'][] = $json_value;
									}
									$newtree[$tree_key]['value']["%n%"] = "";
								} else {
									$newtree[$tree_key]['value'] = $json[$tree_key];
								}
							} else {
								$newtree[$tree_key]['value'] = array("%n%" => "");
							}
						} else {
							if ($this->JSON->json_key_exist($tree_value, "options")) {
								$newtree[$tree_key]['options'] = $tree_value['options'];
							}

							if ($this->JSON->json_key_exist($json, $tree_key)) {
								$newtree[$tree_key]['value'] = $this->JSON->get_json_key($json, $tree_key);
							} else {
								$newtree[$tree_key]['value'] = "";
							}
						}
					}
				}
			}

			return $newtree;
		}

		public function create_form($json, $id_array = array(), $options = array()) {
			$html = "";

			if ($this->JSON->json_key_exist($json, "%n%")) {
				$html .= "<div class='a-paramtree a-sortable'>";
				foreach ($json as $json_key => $json_value) {
					$id_array_new = $id_array;
					$id_array_new[] = $json_key;

					$tab_header = implode(" > ", $id_array_new);
					if (isset($options['tab_header'])) {
						if (isset($json_value[$options['tab_header']])) {
							if (!empty($json_value[$options['tab_header']]['value'])) {
								$tab_header = $json_value[$options['tab_header']]['value'];
							}
						}
					}

					$options_new = array();
					$add_class = "";
					if ($json_key === "%n%") {
						$add_class = "a-paramtree__item-toadd";
						$options_new['hide'] = true;
					} else {
						$add_class = "a-sortable__item";
					}

					$header_class = "";
					if (isset($_COOKIE[implode("|", $id_array_new)."_header"])) {
						$header_class = "a-paramtree__header-active";
						$options_new['active'] = true;
					}

					$html .= "<div class='a-paramtree__item ".$add_class."' data-id='".implode("|", $id_array_new)."'>";
					$html .= "<div class='a-paramtree__header ".$header_class."' data-id='".implode("|", $id_array_new)."_header"."'>";
					
					$html .= "<div class='a-paramtree__header-text'>";

					$html .= "<div class='a-paramtree__left'>";
					$html .= "<i class='a-paramtree__drag icon-drag a-sortable__handler'></i>";
					$html .= "</div>";
					$html .= $tab_header."</div>";
					$html .= "<a href='' class='a-paramtree__remove i-paramtree__remove'>Удалить</a>";
					$html .= "</div>";
					
					$html .= $this->create_form($json_value, $id_array_new, $options_new);
					$html .= "</div>";
				}
				$html .= "<a href='#' class='a-paramtree__add' data-target_id='".implode("|", $id_array_new)."'>Добавить элемент</a>";
				$html .= "</div>";
			} else {
				$class_active = "";
				if (isset($options['active'])) {
					$class_active = "a-paramtree-active";
				}
				$html .= "<div class='a-paramtree ".$class_active."'>";
				foreach ($json as $json_key => $json_value) {
					$id_array_new = $id_array;
					$id_array_new[] = str_ireplace("%n%", "-n-", $json_key);

					$disabled = "";
					if (isset($options['hide'])) {
						if ($options['hide']) {
							$disabled = "disabled";
						}
					}

					if ($this->JSON->json_key_exist($json_value, "children")) {
						$options_new = array();
						if ($this->JSON->json_key_exist($json_value, "tab_header")) {
							$options_new['tab_header'] = $json_value['tab_header'];
							if (isset($options['hide'])) {
								$options_new['hide'] = $options['hide'];
							}
						}
						$html .= "<div class='a-paramtree__item' data-id='".implode("|", $id_array_new)."'>";
						$html .= "<label class='a-label'>".$json_value['name']."</label>";
						if (isset($json_value['desc'])) {
							if ($json_value['desc'] != "") {
								$html .= "<br><small class='a-paramtree__desc'>".$json_value['desc']."</small>";
							}
						}
						$html .= $this->create_form($json_value['children'], $id_array_new, $options_new);
						$html .= "</div>";
					} else if ($this->JSON->json_key_exist($json_value, "children_type")) {
						$html .= "<div class='a-paramtree__item' data-id='".implode("|", $id_array_new)."'>";
						$html .= "<label class='a-label'>".$json_value['name']."</label>";
						$html .= "<div class='a-paramtree a-sortable'>";
						foreach ($json_value['value'] as $value_key => $value_value) {
							$id_array_new_2 = $id_array_new;
							$id_array_new_2[] = str_ireplace("%n%", "-n-", $value_key);

							$name = $id_array_new_2[0];
							for ($i = 1; $i < count($id_array_new_2); $i++) { 
								$name .= "[".$id_array_new_2[$i]."]";
							}
							$id = implode("_", $id_array_new_2);

							$value_options = array();
							if (isset($json_value['options'])) {
								$value_options = $json_value['options'];
							}

							$add_class = "";
							if ($value_key === "%n%") {
								$add_class .= " a-paramtree__item-toadd";
								$disabled = "disabled";
							} else {
								$add_class .= " a-sortable__item";
							}
							if ($json_value['children_type'] == "file") {
								$add_class .= " a-paramtree__item-inline";
							}

							$handler_class = "a-sortable__handler";

							$html .= "<div class='a-paramtree__item ".$add_class."' data-id='".implode("|", $id_array_new_2)."'>";
							if ($json_value['children_type'] != "file") {
								$html .= "<div class='a-paramtree__left'>";
								$html .= "<i class='a-paramtree__drag icon-drag a-sortable__handler'></i>";
								if ($json_value['children_type'] == "wysiwyg" || $json_value['children_type'] == "textarea") {
									$html .= "<a href='#' class='a-paramtree__children-remove i-paramtree__remove'><i class='icon-remove'></i></a>";
								}
								$html .= "</div>";
								if ($json_value['children_type'] != "wysiwyg" && $json_value['children_type'] != "textarea") {
									$html .= "<div class='a-paramtree__right'>";
									$html .= "<a href='#' class='a-paramtree__children-remove i-paramtree__remove'><i class='icon-remove'></i></a>";
									$html .= "</div>";
								}
								$handler_class = "";
							}
							if ($json_value['children_type'] == "file") {
								$html .= $this->form_file($value_value, $id, $name, $handler_class, $disabled);
							} else {
								$html .= "<div class='a-paramtree__drag-item'>";
								$html .= $this->input($json_value['children_type'], $value_value, $id, $name, false, $value_options, $handler_class, $disabled);
								$html .= "</div>";
							}
							
							$html .= "</div>";
						}
						if ($json_value['children_type'] == "file") {
							$html .= "<a href='#' class='a-file i-filemanager' data-target_id='".implode("|", $id_array_new_2)."' data-mfp-src='".BASE_URL."/admin/filemanager/dialog.php?type=2&field_id=".$id."'>";
							$html .= "<span class='a-file__block'>";
							$html .= "<span class='a-file__center'><span class='a-file__add'>+</span></span>";
							$html .= "</span>";
							$html .= "</a>";
						} else {
							$html .= "<a href='#' class='a-paramtree__add' data-target_id='".implode("|", $id_array_new_2)."'>Добавить элемент</a>";
						}
						$html .= "</div>";
						if (isset($json_value['desc'])) {
							if ($json_value['desc'] != "") {
								$html .= "<small class='a-paramtree__desc'>".$json_value['desc']."</small>";
							}
						}
						$html .= "</div>";
					} else {
						$name = $id_array_new[0];
						for ($i = 1; $i < count($id_array_new); $i++) { 
							$name .= "[".$id_array_new[$i]."]";
						}
						$id = implode("_", $id_array_new);

						$value_options = array();
						if (isset($json_value['options'])) {
							$value_options = $json_value['options'];

							$structure = $this->JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_PATH)['structure']['pages'];
							if ($value_options == "%pages%") {
								$current = (isset($options['hide'])) ? "%n%" : $json['url']['value'];
								$value_options = $this->get_pages($structure, $current);
							}
							if ($value_options == "%templates%") {
								$value_options = $this->get_templates($structure);
							}
						}

						$html .= "<div class='a-paramtree__item' data-id='".implode("|", $id_array_new)."'>";
						$html .= "<label class='a-label'>".$json_value['name']."</label><br>";
						if ($json_value['type'] == "file") {
							$html .= $this->form_file($json_value['value'], $id, $name, "", $disabled, true);
						} else {
							$html .= $this->input($json_value['type'], $json_value['value'], $id, $name, false, $value_options, "", $disabled);
						}
						if (isset($json_value['desc'])) {
							if ($json_value['desc'] != "") {
								$html .= "<small class='a-paramtree__desc'>".$json_value['desc']."</small>";
							}
						}

						$html .= "</div>";
					}
				}
				$html .= "</div>";
			}

			return $html;
		}

		public function form_file($value, $id, $name, $addClass = "", $attributes = "", $single = false) {
			$html = "";

			$ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
			$filename = "";
			$file_remove_class = "";
			$file_picture_class = "";
			if ($value != "") {
				$file_remove_class = "a-file__remove-active";
				$filename = pathinfo($value, PATHINFO_FILENAME).".".$ext;
			}

			switch ($ext) {
				case 'jpg':
				case 'jpeg':
				case 'gif':
				case 'png':
				case 'svg':
					unset($ext);
					break;
				
				default:
					break;
			}

			if (!isset($ext)) {
				$file_picture_class = "a-file__picture-exist";
			}

			$html .= "<div class='a-file'>";
			$html .= "<div class='a-file__block ".$addClass."'>";
			$html .= "<div class='a-file__picture ".$file_picture_class."'>";
			if (isset($ext)) {
				$html .= "<img class='a-file__image' src=''>";
			} else {
				$html .= "<img class='a-file__image' src='".$value."'>";
			}
			$html .= "</div>";
			$html .= "<span class='a-file__add'><i class='icon-file'></i>";
			$html .= "<span class='a-file__ext'>";
			if (isset($ext) && $value != "") {
				$html .= ".".$ext;
			}
			$html .= "</span>";
			$html .= "</span>";
			$html .= "<div class='a-file__overlay'>";
			$html .= "<div class='a-file__center'>";
			$html .= "<div class='a-file__change i-filemanager' data-mfp-src='".BASE_URL."/admin/filemanager/dialog.php?type=2&field_id=".$id."'><i class='icon-upload'></i></div>";
			if ($single) {
				$html .= "<div class='a-file__remove i-paramtree__clear ".$file_remove_class."'><i class='icon-remove'></i></div>";
			} else {
				$html .= "<div class='a-file__remove i-paramtree__remove ".$file_remove_class."'><i class='icon-remove'></i></div>";
			}
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			$html .= "<div class='a-file__name' title='".$filename."'>".$filename."</div>";
			$html .= $this->input("hidden", $value, $id, $name, false, array(), "a-file__input", $attributes);
			$html .= "</div>";

			return $html;
		}

		public function check_system() {
			$config = $this->JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_PATH);
			$config_tree = $this->JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_TREE_PATH);

			$config_default = unserialize(CONFIG_DEFAULT);
			$config_tree_default = unserialize(CONFIG_TREE_DEFAULT);

			if (!file_exists($_SERVER['DOCUMENT_ROOT'].BASE_URL.FILEMANAGER_PATH)) {
				mkdir($_SERVER['DOCUMENT_ROOT'].BASE_URL.FILEMANAGER_PATH, 0777);
			}

			$config = array_replace_recursive($config_default, $config);
			$config_tree = array_replace_recursive($config_tree_default, $config_tree);

			$this->JSON->save_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_PATH, $config);
			$this->JSON->save_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_TREE_PATH, $config_tree);
		}

		//remove %n% items from json
		public function clear_empty_fields($json) {
			$new_json = array();

			foreach ($json as $key => $value) {
				if ($key !== "%n%") {
					if (is_array($value)) {
						$new_json[$key] = $this->clear_empty_fields($value);
					} else {
						$new_json[$key] = $value;
					}
				}
			}

			return $new_json;
		}

		public function get_undefined_params($json, $tree, $exclude = array(), $key_array = array()) {
			$param_list = array();
			if (count($tree) > 0) {
				foreach ($tree as $key => $value) {
					$temp_value = array();
					$temp_key_array = $key_array;
					array_push($temp_key_array, $key);
					
					$temp_value['key'] = $temp_key_array;
					$temp_value['name'] = $this->JSON->get_json_key($value, 'name');
					$temp_value['desc'] = $this->JSON->get_json_key($value, 'desc');
					$temp_value['type'] = $this->JSON->get_json_key($value, 'type');
					$temp_value['tab_header'] = $this->JSON->get_json_key($value, 'tab_header');
					
					if (isset($value['children'])) {
						$temp_value['parent'] = true;

						if (!$this->JSON->json_key_exist($exclude, $key)) {
							$param_list[] = $temp_value;
						}

						$new_exclude = array();
						if ($this->JSON->get_json_key($exclude, $key)) {
							if ($this->JSON->get_json_key($exclude[$key], 'children')) {
								$new_exclude = $exclude[$key]['children'];
							}
						}

						$param_list = array_merge($param_list, $this->get_undefined_params($json, $value['children'], $new_exclude, $temp_key_array));
					} else {
						$temp_value['options'] = (isset($value['options'])) ? $value['options'] : array();
						$temp_value['children_type'] = (isset($value['children_type'])) ? $value['children_type'] : "";
						$temp_value['value'] = $this->JSON->get_all_values($json, $temp_key_array);

						if (!$this->JSON->json_key_exist($exclude, $key)) {
							$param_list[] = $temp_value;
						}
					}
				}
			}
			
			return $param_list;
		}

		public function get_undef_count($array) {
			$undef_count = 0;

			foreach ($array as $value) {
				if ($value['name'] == "" || $value['type'] == "") {
					$undef_count++;
				}
			}

			return $undef_count;
		}

		public function get_children($json, $key, $level = 0) {
			$array = array();

			if (count($json) > 0) {
				if (count($key) == $level) {
					foreach ($json as $json_key => $json_value) {
						if ($json_value['type'] == "text") {
							$array[$json_key] = $json_value['name'];
						}
					}
				} else {
					foreach ($json as $json_key => $json_value) {
						if ($json_key == $key[$level]) {
							$array = array_merge($array, $this->get_children($json_value['children'], $key, ($level+1)));
						}
					}
				}
			}

			return $array;
		}

		public function check_alias($json, $key, $level = 0) {
			$exist = true;

			if (count($key) != 0) {
				if (count($key) - 1 == $level) {
					if (isset($json[$key[$level]])) {
						$exist = false;
					}
				} else {
					if (isset($json[$key[$level]])) {
						foreach ($json as $json_key => $json_value) {
							if (isset($json_value['children'])) {
								$exist = $this->check_alias($json_value['children'], $key, ($level+1));
							}
						}
					}
				}
			} else {
				$exist = false;
			}

			return $exist;
		}

		public function get_pages($structure, $current) {
			$pages = array();

			$pages[""] = "Корень сайта";
			foreach ($structure as $key => $value) {
				if ($value['url'] != $current && $value['url'] != "" && $current != "") {
					$pages[$value['url']] = $value['name'];
				}
			}

			return $pages;
		}

		public function get_templates($structure) {
			$templates = array();

			$templates = $this->get_files_name($_SERVER['DOCUMENT_ROOT'].BASE_URL.TEMPLATE_PATH, array("html", "php"), true);

			return $templates;
		}

		public function get_page($structure, $route, $level = 0, $parent = "") {
			$data = array();
			
			if (count($route) - 1 == $level) {
				foreach ($structure as $key => $value) {
					if ($value['url'] === $route[$level]) {
						if ($value['parent'] == $parent) {
							$data = $value;
						} else {
							$data = false;
						}
						break;
					} else {
						$data = false;
					}
				}
			} else {
				foreach ($structure as $key => $value) {
					if ($value['url'] === $route[$level]) {
						if ($value['parent'] == $parent) {
							$data = $this->get_page($structure, $route, ($level+1), $route[$level]);
						} else {
							$data = false;
						}
						break;
					} else {
						$data = false;
					}
				}
			}

			return $data;
		}

		public function get_menu($structure) {
			$menu = array();

			foreach ($structure as $key => $value) {
				$menu_item = $value;
				$menu_item['link'] = $this->get_full_url($structure, $value['url']);
				$menu[] = $menu_item;
			}

			return $menu;
		}

		public function get_full_url($structure, $alias, $full_url = "") {
			$new_url = "/";

			$new_url = $new_url.$alias;
			foreach ($structure as $key => $value) {
				if ($value['url'] == $alias && $value['parent'] != "") {
					$full_url = $this->get_full_url($structure, $value['parent'], $full_url);
					break;
				}
			}

			$full_url = $full_url.$new_url;

			return $full_url;
		}

		/*
			$subject - тема письма
			$data - двумерный массив данных
			$template - название шаблона лежащего в директории указанной в config.php, например default.php
			$files - массив абсолютных путей до файлов на севрере, которые необходимо отправить
			$emailto - массив email на которые отправлять письма, по умолчанию берутся из config.json
		*/
		public function mail($subject, $data, $template = '', $files = array(), $emailto = array()) {
			$config = $this->JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.CONFIG_PATH)['config'];

			$message = "";
			$altmessage = "";

			foreach ($data as $key => $value) {
				$altmessage .= $key.": ".$value."\r\n";
			}

			if (file_exists($_SERVER['DOCUMENT_ROOT'].BASE_URL.MAIL_TEMPLATE_PATH.'/'.$template) && $template != "") {
				ob_start();
				include($_SERVER['DOCUMENT_ROOT'].BASE_URL.MAIL_TEMPLATE_PATH.'/'.$template);
				$message = ob_get_contents();
				ob_end_clean();
			} else {
				$message = $altmessage;
			}

			$mail_data = $this->JSON->get_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.MAIL_DATA_PATH);
			$mail_data[] = $this->create_mail_item($subject, $data, $files);
			$this->JSON->save_json($_SERVER['DOCUMENT_ROOT'].BASE_URL.MAIL_DATA_PATH, $mail_data);

			$emailto = (!empty($emailto)) ? $emailto : $config['email_to'];
			$this->mailer->CharSet   = "UTF-8";
			$this->mailer->From      = $config['email_from'];
			$this->mailer->FromName  = $_SERVER['SERVER_NAME'];
			$this->mailer->Subject   = $subject;
			$this->mailer->Body      = $message;
			$this->mailer->AltBody   = $altmessage;
			$this->mailer->isHTML(true);

			if (count($files) > 0) {
				foreach ($files as $file) {
					$this->mailer->addAttachment($file);
				}
			}

			foreach ($emailto as $mail_address) {
				$this->mailer->AddAddress($mail_address);
			}

			$this->mailer->send();
		}

		public function create_mail_item($subject, $data, $files) {
			$item = array(
				'subject' => $subject, 
				'date' => date('Y-m-d G:i:s')
			);
			$item['data'] = array();
			foreach ($data as $key => $value) {
				$item['data'][$key] = $value;
			}
			$item['files'] = array();
			foreach ($files as $key => $value) {
				$item['files'][$key] = $value;
			}

			return $item;
		}

		public function create_mail_list($data) {
			$mail_list = '<div class="a-maillist">';

			foreach ($data as $mail_key => $mail) {
				$header_class = '';
				$content_class = '';
				if (isset($_COOKIE["mail|".$mail_key])) {
					$header_class = "i-accordeon-header-active";
					$content_class = "i-accordeon-content-active";
				}

				$mail_list .= '<div class="a-maillist__item" data-id="mail|'.$mail_key.'">';
				$mail_list .= '<div class="a-maillist__header i-accordeon-header '.$header_class.'">';
				$mail_list .= '<div class="a-maillist__right">';
				$mail_list .= '<div class="a-maillist__date">'.$this->date_format($mail['date']).'</div>';
				$mail_list .= '<a href="" class="a-maillist__remove i-maillist__remove">Удалить</a>';
				$mail_list .= '</div>';
				$mail_list .= '<div class="a-maillist__subject">'.$mail['subject'].'</div>';
				$mail_list .= '<input type="hidden" id="mail_'.$mail_key.'_subject" name="mail['.$mail_key.'][subject]" value="'.$mail['subject'].'">';
				$mail_list .= '<input type="hidden" id="mail_'.$mail_key.'_date" name="mail['.$mail_key.'][date]" value="'.$mail['date'].'">';
				$mail_list .= '</div>';
				$mail_list .= '<div class="a-maillist__content '.$content_class.'">';
				if (isset($mail['files'])) {
					if (count($mail['files']) > 0) {
						$mail_list .= '<div class="a-maillist__files">';
						foreach ($mail['files'] as $file_key => $file) {
							$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
							$filename = "";
							$file_remove_class = "";
							$file_picture_class = "";
							if ($file != "") {
								$file_remove_class = "a-file__remove-active";
								$filename = pathinfo($file, PATHINFO_FILENAME).".".$ext;
							}

							switch ($ext) {
								case 'jpg':
								case 'jpeg':
								case 'gif':
								case 'png':
								case 'svg':
									unset($ext);
									break;
								
								default:
									break;
							}

							$mail_list .= '<a href="'.$file.'" target="_blank" class="a-file a-file-small a-file-multi">';
							$mail_list .= '<span class="a-file__block">';
							if (!isset($ext)) {
								$mail_list .= '<span class="a-file__picture ">';
								$mail_list .= '<img class="a-file__image" src="'.$file.'">';
								$mail_list .= '</span>';
							} else {
								$mail_list .= '<span class="a-file__add">';
								$mail_list .= '<i class="icon-file"></i>';
								$mail_list .= '<span class="a-file__ext">.'.$ext.'</span>';
								$mail_list .= '</span>';
							}
							$mail_list .= '</span>';
							$mail_list .= '<span class="a-file__name" title="">'.$filename.'</span>';
							$mail_list .= '</a>';
							$mail_list .= '<input type="hidden" id="mail_'.$mail_key.'_files_'.$file_key.'_'.$file.'" name="mail['.$mail_key.'][files]['.$file_key.']['.$file.']" value="'.$file.'">';
						}
						$mail_list .= '</div>';
					}
				}
				if (count($mail['data']) > 0) {
					$mail_list .= '<div class="a-maillist__text">';
					foreach ($mail['data'] as $key => $value) {
						$mail_list .= '<div class="a-maillist__row">';
						$mail_list .= '<div class="a-maillist__label">'.$key.':</div>';
						$mail_list .= '<div class="a-maillist__value">'.$value.'</div>';
						$mail_list .= '</div>';
						$mail_list .= '<input type="hidden" id="mail_'.$mail_key.'_data_'.$key.'" name="mail['.$mail_key.'][data]['.$key.']" value="'.$value.'">';
					}
					$mail_list .= '</div>';
				}
				$mail_list .= '</div>';
				$mail_list .= '</div>';
			}
			$mail_list .= '</div>';

			return $mail_list;
		}

		public function date_format($date) {
			$new_date = '';
			$date_to_time = strtotime($date);
			$current_date = strtotime(date("Y-m-d G:i:s"));

			$nmonth = array(
				1 => 'янв',
				2 => 'фев',
				3 => 'мар',
				4 => 'апр',
				5 => 'мая',
				6 => 'июн',
				7 => 'июл',
				8 => 'авг',
				9 => 'сен',
				10 => 'окт',
				11 => 'ноя',
				12 => 'дек'
			);

			if (date("Y.m.d", $date_to_time) == date("Y.m.d", $current_date)) {
				$new_date = date("G:i", $date_to_time);
			} else if (date("Y.m", $date_to_time) == date("Y.m", $current_date)) {
				$new_date = date("d", $date_to_time)." ".$nmonth[date("n", $date_to_time)];
			} else if (date("Y", $date_to_time) != date("Y", $current_date)) {
				$new_date = date("d", $date_to_time)." ".$nmonth[date("n", $date_to_time)]." ".date("Y", $date_to_time);
			}

			return $new_date;
		}
	}
?>