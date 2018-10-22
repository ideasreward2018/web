<?php
/*
  Plugin Name: Smooth Scroll — Scroll site without jerky and clunky effects
  Plugin URI: https://codecanyon.net/user/42theme/?ref=42theme
  Description: Smooth Scroll plugin will make your WordPress site cool by making the scroll movement of the mouse wheel smooth. Making Google Chrome And WebKit Based Browsers Scroll Smoother.
  Author: 42Theme
  Version: 1.0.0
  Author URI: https://codecanyon.net/user/42theme/portfolio/?ref=42theme
 */

/**
 * 
 * Smooth Scroll — Scroll site without jerky and clunky effects, WordPress version. 42Theme.com useful plugins for WordPress. Exclusively on Envato Market: http://codecanyon.net/user/42theme/portfolio?ref=42theme
 * @encoding     UTF-8
 * @version      1.0.0
 * @copyright    Copyright (C) 2017 42theme (https://42theme.com). All rights reserved.
 * @license      http://codecanyon.net/licenses/standard?ref=42theme
 * @author       Alexander Khmelnitskiy (hot@42theme.com)
 * @support      support@42theme.com
 * 
 */
if (!class_exists('t42_smoothscroll')) {

    /**
     * Core class used to implement a Smooth Scroll plugin.
     *
     * This is used to define internationalization, admin-specific hooks, and
     * public-facing site hooks.
     *
     * @since 1.0.0 
     */
    class t42_smoothscroll {

        /**
         * Sets up a new Smooth Scroll instance.
         *
         * @since 1.0.0
         * @access public
         */
        public function __construct() {

            /** Load translation */
            $this->load_textdomain();

            /** Add plugin links */
            add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($this, 'add_links'));

            /** Add plugin script */
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts') ); // Add JS
            
        }

        /**
         * Loads the Smooth Scroll translated strings.
         *
         * @since 1.0.0
         * @access public
         */
        public function load_textdomain() {
            load_plugin_textdomain('t42-smoothscroll', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        /**
         * Add "42Theme.com" and  "Envato Profile" links on plugin page.
         *
         * @since 1.0.0
         * @access public
         *
         * @param array $links Current links: Deactivate | Edit 
         */
        public function add_links($links) {

            array_push($links, '<a title="We are developing beautiful themes and applications for the web!" href="https://42theme.com" target="_blank"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4KPHN2ZyBpZD0iTGF5ZXJfMSIgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbDpzcGFjZT0icHJlc2VydmUiIHZpZXdCb3g9IjAgMCAzMDAgMzAwIiBoZWlnaHQ9IjMwMCIgd2lkdGg9IjMwMCIgdmVyc2lvbj0iMS4xIiB5PSIwcHgiIHg9IjBweCIgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3JnL25zIyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIj48bWV0YWRhdGEgaWQ9Im1ldGFkYXRhNDUiPjxyZGY6UkRGPjxjYzpXb3JrIHJkZjphYm91dD0iIj48ZGM6Zm9ybWF0PmltYWdlL3N2Zyt4bWw8L2RjOmZvcm1hdD48ZGM6dHlwZSByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIi8+PGRjOnRpdGxlLz48L2NjOldvcms+PC9yZGY6UkRGPjwvbWV0YWRhdGE+PHN0eWxlIGlkPSJzdHlsZTIiIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojNDQ0NDQ0O30KCS5zdDF7ZmlsbDojNENBRjUwO30KPC9zdHlsZT48ZyBpZD0iZzQ1MzUiIHRyYW5zZm9ybT0ibWF0cml4KC44NDAzNCAwIDAgLjg0MDM0IC0zODkuNSAtMTEwLjQyKSI+PHBhdGggaWQ9InBhdGgxNCIgZmlsbD0iIzQ0NCIgY2xhc3M9InN0MCIgZD0ibTYyMi43IDMyOS43di03Ny4xaC0xNmwtNTIuMiA3OS43djkuN2g1M3YyNy4yaDE1LjF2LTI3LjJoMTYuNHYtMTIuM3ptLTE1LjEgMGgtMzYuN2wzNS01NCAxLjgtMy4yeiIvPjxwYXRoIGlkPSJwYXRoMTYiIGZpbGw9IiM0NDQiIGNsYXNzPSJzdDAiIGQ9Im02NzAuOCAzNTcuMSAzMS41LTMzLjNjMTQuNy0xNS42IDIyLjEtMjkuMSAyMi4xLTQwLjQgMC05LjMtMy4yLTE2LjctOS41LTIyLjMtNi40LTUuNi0xNS04LjQtMjUuOS04LjQtMTEuNyAwLTIxIDMuMy0yOCA5LjhzLTEwLjUgMTUuMS0xMC41IDI1LjZoMTQuOWMwLTcuNCAyLjEtMTMuMSA2LjItMTcuMnM5LjktNi4yIDE3LjMtNi4yYzYuMiAwIDExLjEgMS45IDE0LjkgNS44IDMuNyAzLjggNS42IDguNyA1LjYgMTQuNyAwIDQuNS0xLjEgOC44LTMuNCAxM3MtNi40IDkuNi0xMi41IDE2LjNsLTQwLjcgNDQuMXYxMC43aDc2LjZ2LTEyLjF6Ii8+PGcgaWQ9InBhdGg0NDcwLTMtNy00LTgtMi0wLTQtNC0zLTktMi0zLTEtMl8yXyI+PHBhdGggaWQ9InBhdGgxOCIgZmlsbD0iIzRjYWY1MCIgY2xhc3M9InN0MSIgZD0ibTY0MiAxNTAuOGM0Mi41IDAgODIuNSAxNi42IDExMi41IDQ2LjYgMzAuMSAzMC4xIDQ2LjYgNzAgNDYuNiAxMTIuNXMtMTYuNiA4Mi41LTQ2LjYgMTEyLjVjLTMwLjEgMzAuMS03MCA0Ni42LTExMi41IDQ2LjZzLTgyLjUtMTYuNi0xMTIuNS00Ni42LTQ2LjYtNzAtNDYuNi0xMTIuNSAxNi42LTgyLjUgNDYuNi0xMTIuNWMzMC0zMC4xIDcwLTQ2LjYgMTEyLjUtNDYuNm0wLTE5LjNjLTk4LjYgMC0xNzguNSA3OS45LTE3OC41IDE3OC41czc5LjkgMTc4LjQgMTc4LjUgMTc4LjQgMTc4LjUtNzkuOSAxNzguNS0xNzguNC03OS45LTE3OC41LTE3OC41LTE3OC41eiIvPjwvZz48L2c+PC9zdmc+Cg==" alt="" style="width: 22px; margin-right: 2px; vertical-align: middle; position: relative; top: -1px;"> 42Theme.com</a>');
            array_push($links, '<a title="Our portfolio on Envato Market" href="https://codecanyon.net/user/42theme/portfolio?ref=42theme" target="_blank"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDkuMjY3IDMwOS4yNjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMwOS4yNjcgMzA5LjI2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxwYXRoIHN0eWxlPSJmaWxsOiNEMDk5NEI7IiBkPSJNMjYwLjk0NCw0My40OTFIMTI1LjY0YzAsMC0xOC4zMjQtMjguOTk0LTI4Ljk5NC0yOC45OTRINDguMzIzYy0xMC42NywwLTE5LjMyOSw4LjY1LTE5LjMyOSwxOS4zMjkgICB2MjIyLjI4NmMwLDEwLjY3LDguNjU5LDE5LjMyOSwxOS4zMjksMTkuMzI5aDIxMi42MjFjMTAuNjcsMCwxOS4zMjktOC42NTksMTkuMzI5LTE5LjMyOVY2Mi44MiAgIEMyODAuMjczLDUyLjE1LDI3MS42MTQsNDMuNDkxLDI2MC45NDQsNDMuNDkxeiIvPgoJPHBhdGggc3R5bGU9ImZpbGw6I0U0RTdFNzsiIGQ9Ik0yOC45OTQsNzIuNDg0aDI1MS4yNzl2NzcuMzE3SDI4Ljk5NFY3Mi40ODR6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojRjRCNDU5OyIgZD0iTTE5LjMyOSw5MS44MTRoMjcwLjYwOWMxMC42NywwLDE5LjMyOSw4LjY1LDE5LjMyOSwxOS4zMjlsLTE5LjMyOSwxNjQuMjk4ICAgYzAsMTAuNjctOC42NTksMTkuMzI5LTE5LjMyOSwxOS4zMjlIMzguNjU4Yy0xMC42NywwLTE5LjMyOS04LjY1OS0xOS4zMjktMTkuMzI5TDAsMTExLjE0M0MwLDEwMC40NjMsOC42NTksOTEuODE0LDE5LjMyOSw5MS44MTR6ICAgIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" alt="" style="width: 16px; vertical-align: middle; position: relative; top: -2px;"> Portfolio</a>');

            return $links;
        }

        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts() {

            // Use minified libraries if SCRIPT_DEBUG is turned off
            $suffix = ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) ? '' : '.min';
            wp_enqueue_script('t42-smoothscroll-js', plugin_dir_url(__FILE__) . 'js/SmoothScroll' . $suffix . '.js', array(), '', true);
            
        }

    } //// End of class t42_smoothscroll

}

/** Execution of the plugin. */
new t42_smoothscroll();
