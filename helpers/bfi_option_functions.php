<?php

@define( "BFI_OPTION_GLOBAL_SETTINGS_NAME", "_global_settings" );

function bfi_get_option( $optionName, $default = false, $namespace = '' ) {
    if ( empty( $namespace ) ) {
        if ( defined( "BFI_THEMENAME" ) ) {
            $namespace = BFI_THEMENAME;
        }
    }

    // If no namespace, then use get_option
    if ( empty( $namespace ) ) {
        return get_option( $optionName, $default );
    }

    // get the global settings option (we store everything in a single option)
    $namespace = strtolower( $namespace );
    $globalSettings = get_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME );

    // if nothing is saved yet, create the option
    if ( $globalSettings === false ) {
        $globalSettings = array();
        update_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME, serialize( $globalSettings ) );
    } else {
        $globalSettings = unserialize( $globalSettings );
    }

    // get the option value
    $val = $default;
    if ( !empty( $globalSettings[$optionName] ) ) {
        $val = $globalSettings[$optionName];
    }

    return apply_filters( 'bfi_get_option', $val, $optionName );
}

function bfi_update_option( $optionName, $value, $namespace = '' ) {
    if ( empty( $namespace ) ) {
        if ( defined( "BFI_THEMENAME" ) ) {
            $namespace = BFI_THEMENAME;
        }
    }

    // If no namespace, then use update_option
    if ( empty( $namespace ) ) {
        return update_option( $optionName, $value );
    }

    // get the global settings option (we store everything in a single option)
    $namespace = strtolower( $namespace );
    $globalSettings = get_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME );

    // if nothing is saved yet, create the option
    if ( $globalSettings === false ) {
        $globalSettings = array();
    } else {
        $globalSettings = unserialize( $globalSettings );
    }

    $globalSettings[$optionName] = $value;
    return update_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME, serialize( $globalSettings ) );
}

function bfi_add_option( $option, $value = '', $namespace = '' ) {
    if ( empty( $namespace ) ) {
        if ( defined( "BFI_THEMENAME" ) ) {
            $namespace = BFI_THEMENAME;
        }
    }

    // If no namespace, then use update_option
    if ( empty( $namespace ) ) {
        return add_option( $optionName, $value );
    }

    // get the global settings option (we store everything in a single option)
    $namespace = strtolower( $namespace );
    $globalSettings = get_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME );

    // if nothing is saved yet, create the option
    if ( $globalSettings === false ) {
        $globalSettings = array();
    } else {
        $globalSettings = unserialize( $globalSettings );
    }

    $globalSettings[$optionName] = $value;
    return update_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME, serialize( $globalSettings ) );
}

function bfi_delete_option( $option, $namespace = '' ) {
    if ( empty( $namespace ) ) {
        if ( defined( "BFI_THEMENAME" ) ) {
            $namespace = BFI_THEMENAME;
        }
    }

    // If no namespace, then use update_option
    if ( empty( $namespace ) ) {
        return delete_option( $optionName );
    }

    // get the global settings option (we store everything in a single option)
    $namespace = strtolower( $namespace );
    $globalSettings = get_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME );

    // if nothing is saved yet, create the option
    if ( $globalSettings === false ) {
        $globalSettings = array();
    } else {
        $globalSettings = unserialize( $globalSettings );
        unset( $globalSettings[$optionName] );
    }

    return update_option( $namespace . BFI_OPTION_GLOBAL_SETTINGS_NAME, serialize( $globalSettings ) );
}