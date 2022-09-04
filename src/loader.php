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

        foreach ($paths as $path) {

            $templates_name_labels = [];

            if (is_dir($path)) {

                $finder = Finder::findFiles('*.php')
                                ->in($path)
                                ->limitDepth(5);

                foreach ($finder as $key => $file) {

                    $relative_filename = str_replace($path . '/', '', $file->getPathname());

                    $file_name_array = explode('.', $relative_filename);

                    // Get template name form file name
                    if (count($file_name_array) === 1) {
                        $name = str_replace('-', ' ', $file_name_array[ 0 ]);
                    } else {
                        $name = ucwords(str_replace('-', ' ', Arrays::get($file_name_array, 0, 'None')));
                    }

                    // Get template name form file comment
                    $headers = [
                        'Name' => __('Loop Template Name', 'wprs'),
                    ];

                    $file_info = get_file_data($key, $headers);

                    if ($file_info[ 'Name' ]) {
                        $option_name = $file_info[ 'Name' ];
                    } else {
                        $option_name = ucfirst($name);
                    }


		    // 模版 $name => 模版注释名称数组
		    $templates_name_labels[ $relative_filename ] = $option_name;

                }

                $all_templates_name_labels = array_merge_recursive($all_templates_name_labels, $templates_name_labels);

            }

        }

        return $all_templates_name_labels;

}
