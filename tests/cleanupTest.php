<?php
/**
 * Test for pickles2/px2-paprika
 */

class cleanupTest extends PHPUnit_Framework_TestCase{

	/**
	 * setup
	 */
	public function setup(){
		$this->fs = new \tomk79\filesystem();
	}

	/**
	 * テスト完了後に原状復帰する
	 */
	public function testCleanup(){

		// 後始末
		$output = $this->passthru( [
			'php',
			__DIR__.'/testdata/standard/.px_execute.php' ,
			'/?PX=clearcache' ,
		] );

		sleep(1);

		$this->fs->rm(__DIR__.'/testdata/standard/paprika-files/_database.sqlite');
		$this->fs->rm(__DIR__.'/testdata/standard/px-files/dist/paprika-files/_database.sqlite');

	}//testCleanup()


	/**
	 * コマンドを実行し、標準出力値を返す
	 * @param array $ary_command コマンドのパラメータを要素として持つ配列
	 * @return string コマンドの標準出力値
	 */
	private function passthru( $ary_command ){
		$cmd = array();
		foreach( $ary_command as $row ){
			$param = '"'.addslashes($row).'"';
			array_push( $cmd, $param );
		}
		$cmd = implode( ' ', $cmd );
		ob_start();
		passthru( $cmd );
		$bin = ob_get_clean();
		return $bin;
	}// passthru()

}
