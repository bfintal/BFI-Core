<?php
/*
Plugin Name: Gambit Framework
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: The BFI / Gambit Framework. This is used as the foundation for all Gambit WordPress themes and contains all of the fundamental functions and classes used throughout the theme. Activating this for other themes will do nothing.
Author: Benjamin Intal
Version: 3.0
Author URI: http://gambit.ph/
*/

/*
FRAMEWORK JOB,
LOAD ALL FUNCTIONS & CLASSES
DOES NOT HOOK INTO ANYTHING
STARTS RUNNING EVERYTHING
 */
BFILoader::initFramework();


if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'bfintal', "BFI-Core" );
}



class BFILoader {
    // Called by framework
    public static function initFramework() {
        if ( !defined( "DS" ) ) {
            if ( !defined( "DIRECTORY_SEPARATOR" ) ) {
                define( "DS", "/" );
            } else {
                define( "DS", DIRECTORY_SEPARATOR );
            }
        }

        if ( is_admin() ) {
            // Load back-end stuff only
            BFILoader::requirePHPFiles( "controllers", plugin_dir_path( __FILE__ ) );
        } else {
            // Load front-end stuff only

        }

        // Load for both back-end and front-end
        BFILoader::requirePHPFiles( "helpers", plugin_dir_path( __FILE__ ) );
        BFILoader::requirePHPFiles( "modules", plugin_dir_path( __FILE__ ) );
    }

    // Called by plugins
    private static function initPlugin( $pluginPath ) {

    }

    // Called by the theme to initialize it
    public static function initTheme() {
        $currTheme = wp_get_theme();
        @define( "BFI_THEMENAME", $currTheme->get( "Name" ) );
        @define( "BFI_THEMEVERSION", $currTheme->get( "Version" ) );

        // Load the theme files
        BFILoader::requirePHPFiles( "application", get_template_directory() );
    }

    public static function requirePHPFiles( $path, $parentDir ) {
        $filenames = self::getPHPFiles( $path, $parentDir );
        foreach ( $filenames as $filename ) {
            require_once( $filename );
        }
    }

    // gets all PHP files in the given path
    private static function getPHPFiles( $path, $parentDir ) {
        // Clean up path
        $path = trim( $path, " /\\" );
        $path = rtrim( $parentDir, " /\\" ) . DS . $path;

        /*
         * Get all the files using GLOB
         */
        $filenames = glob( $path . DS . "*.php" );
        if ( is_array( $filenames ) && count( $filenames ) > 0 && $filenames !== false ) {
            return $filenames;
        }

        /*
         * If glob doesn't work, try opendir and readdir
         */
        $filenames = array();
        if ( !is_dir( $path ) ) return $filenames;

        if ( $handle = opendir( $path ) ) {
            while ( false !== ( $file = readdir( $handle ) ) ) {
                if ( $file != "." && $file != ".." && fnmatch( "*.php", $file ) ) {
                    $filenames[] = $path . DS . $file;
                }
            }
        }
        closedir( $handle );
        return $filenames;
    }
}