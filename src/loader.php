<?php

use Nette\Utils\Arrays;
use Nette\Utils\Finder;


/**
 * 获取目录中的模版片段
 *
 * @param $paths array 包含模版的目录路径，可以包含多个路径，后面路径中的文件会覆盖前面路径中的
 *
 * @return mixed
 */
function wprs_get_templates_option( $paths ) {

	$all_templates_name_labels = [];

	foreach ( $paths as $path ) {

		$templates_name_labels = [];

		if ( is_dir( $path ) ) {

			$finder = Finder::findFiles( '*.php' )
			                ->in( $path );

			foreach ( $finder as $key => $file ) {

				$filename        = $file->getFilename();
				$file_name_array = explode( '-', $filename );
				$name            = Arrays::get( $file_name_array, 1, 'None' );

				$headers = [
					'Name' => __( 'Loop Template Name', 'wprs' ),
				];

				$file_info = get_file_data( $key, $headers );

				// 获取模板名称
				if ( $file_info[ 'Name' ] ) {
					$option_name = $file_info[ 'Name' ];
				} else {
					$option_name = ucfirst( $name );
				}

				// 模版 $name => 模版注释名称数组
				$templates_name_labels[ explode( '.', $name )[ 0 ] ] = $option_name;

			}

		}

		$all_templates_name_labels[] = $templates_name_labels;

	}

	// 循环合并所有数组
	$templates = wp_parse_args( $all_templates_name_labels[ 1 ], $all_templates_name_labels[ 0 ] );

	return $templates;

}