<?php
class MyTemplate
{
    function show($tpl_file_path)
    {
        extract((array)$this);
        include($tpl_file_path);
    }

    function output($tpl_file_path, $id)
    {
        ob_start();
        extract((array)$this);
        include($tpl_file_path);
        $contents = ob_get_contents();
        $filename = './tchk/'.$id.'.html';
        $fp = fopen($filename, 'w');
        fwrite( $fp, $contents);
        //出力バッファリングを終了
        ob_end_clean();
        fclose( $fp );// メッセージ表示
        echo $contents;
    }

    function output_index($tpl_file_path)
    {
        ob_start();
        extract((array)$this);
        include($tpl_file_path);
        $contents = ob_get_contents();
        $filename = './index.html';
        $fp = fopen($filename, 'w');
        fwrite( $fp, $contents);
        //出力バッファリングを終了
        ob_end_clean();
        fclose( $fp );// メッセージ表示
        echo $contents;
    }

}
?>