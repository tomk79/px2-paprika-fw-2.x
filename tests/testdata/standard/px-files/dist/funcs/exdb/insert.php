<?php
// chdir
chdir(__DIR__);

// autoload.php をロード
$tmp_path_autoload = __DIR__;
while(1){
    if( is_file( $tmp_path_autoload.'/vendor/autoload.php' ) ){
        require_once( $tmp_path_autoload.'/vendor/autoload.php' );
        break;
    }

    if( $tmp_path_autoload == dirname($tmp_path_autoload) ){
        // これ以上、上の階層がない。
        break;
    }
    $tmp_path_autoload = dirname($tmp_path_autoload);
    continue;
}
unset($tmp_path_autoload);

$paprika = new \tomk79\pickles2\paprikaFramework2\paprika(json_decode('{"file_default_permission":"775","dir_default_permission":"775","filesystem_encoding":"UTF-8","session_name":"PXSID","session_expire":1800,"directory_index":["index.html"],"realpath_controot":"../../","realpath_controot_preview":"../../../../","realpath_homedir":"../../../","path_controot":"/","realpath_files":"./insert_files/"}'), false);

// 共通の prepend スクリプトを実行
if(is_file($paprika->env()->realpath_homedir.'prepend.php')){
    include($paprika->env()->realpath_homedir.'prepend.php');
}
?>
<?php
if( !isset($paprika) ){
	echo '{$main_contents}'."\n";
	return;
}

$form = $paprika->form();
$content = $form->form([
	[
		"title"=> [
			"type"=> "text",
			"label"=> "タイトル",
			"description"=>"タイトルを入力してください。",
			"required"=>true,
			"min"=>4,
			"max"=>18,
		],
		"description"=> [
			"type"=> "textarea",
			"label"=> "説明",
		],
	],
], null, function($paprika, $user_input_values){
	// 成功したら true を返します。
	// 失敗時には、 失敗画面に表示するHTMLを返してください。
	// var_dump($user_input_values);
	// return '<p style="color: #f00;">失敗しました。</p>';
	$exdb = $paprika->exdb();
	$result = $exdb->insert('insert_test', [
		'record_title'=>$user_input_values['title'],
		'description'=>$user_input_values['description'],
	]);

	if(!$result){
		return '<p style="color: #f00;">失敗しました。</p>';
	}

	return true;
}, [
]);

$tpl = $paprika->bind_template(
	array('{$main_contents}'=>$content)
);
echo $tpl;
exit();