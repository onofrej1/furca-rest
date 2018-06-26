<?php

namespace app;

class Generator
{

  /**
   * Simple PHP Templating function
   *
   * @param $names  - string|array Template names
   * @param $args   - Associative array of variables to pass to the template file.
   * @return string - Output of the template file. Likely HTML.
   */
  public static function parse( $names, $args ){
    // allow for single file names
    if ( !is_array( $names ) ) {
      $names = array( $names );
    }
    // try to find the templates
    $template_found = false;
    foreach ( $names as $name ) {
      $file = __DIR__ . '/templates/' . $name;
      if ( file_exists( $file ) ) {
        $template_found = $file;
        // stop after the first template is found
        break;
      } 
    }
    // fail if no template file is found
    if ( ! $template_found ) {
      return '';
    }
    // Make values in the associative array easier to access by extracting them
    if ( is_array( $args ) ){
      extract( $args );
    }
    // buffer the output (including the file is "output")
    ob_start();
      include $template_found;
    return ob_get_clean();
  }

}
