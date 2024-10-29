<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.mapo-dev.com
 * @since      1.0.0
 *
 * @package    Advice_Box
 * @subpackage Advice_Box/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Advice_Box
 * @subpackage Advice_Box/includes
 * @author     Marcin Poholski <mapo@mapo-dev.com>
 */
class Advice_Box
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Advice_Box_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->plugin_name = 'advice-box';
        $this->version = '1.0.2';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Advice_Box_Loader. Orchestrates the hooks of the plugin.
     * - Advice_Box_i18n. Defines internationalization functionality.
     * - Advice_Box_Admin. Defines all hooks for the admin area.
     * - Advice_Box_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-advice-box-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-advice-box-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-advice-box-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-advice-box-public.php';

        $this->loader = new Advice_Box_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Advice_Box_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Advice_Box_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Advice_Box_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Advice_Box_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        add_shortcode("advice_box", array($this, "advicebox_tag_function"));
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Advice_Box_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * @param $attr
     * @param $content
     *
     * @internal param $attributes
     * @since   1.0.0
     */
    public function advicebox_tag_function($attr, $content)
    {
        $close = false;
        if(isset($attr['close']) && $attr['close'] == "true") {
             $close = true;
        }

        $closeScript = "onclick=\"jQuery(this).parents('.advice-box').remove();\"";

        if (isset($attr['type'])) {
            if ($attr['type'] == "positive") {
                $return = '
                <div class="advice-box green">
                    <i class="fa fa-thumbs-up fa-4x pull-left positive"></i>';

                    if($close) {
                        $return .= '
                        <a href="javascript:void(0)" ' . $closeScript . '>
                            <i class="fa fa-times-circle pull-right fa-2x positive"></i>
                        </a>
                        ';
                    }

                    $return .= $content;
                    $return .= '<div class="clear"></div></div>';
            } else if ($attr['type'] == "negative") {
                $return = '
                <div class="advice-box red">
                    <i class="fa fa-minus-circle fa-4x pull-left negative"></i>';

                if($close) {
                    $return .= '
                        <a href="javascript:void(0)" ' . $closeScript . '>
                            <i class="fa fa-minus-circle pull-right fa-2x negative"></i>
                        </a>
                        ';
                }

                $return .= $content;
                $return .= '<div class="clear"></div></div>';
            } else if($attr['type'] == 'info') {
                $return = '
                <div class="advice-box grey">
                    <i class="fa fa-exclamation-circle fa-4x pull-left grey"></i>';

                if($close) {
                    $return .= '
                        <a href="javascript:void(0)" ' . $closeScript . '>
                            <i class="fa fa-minus-circle pull-right fa-2x grey"></i>
                        </a>
                        ';
                }

                $return .= $content;
                $return .= '<div class="clear"></div></div>';
            }
        } else {
            $return = '
                <div class="advice-box grey">
                    <i class="fa fa-exclamation-circle fa-4x pull-left grey"></i>';

            if($close) {
                $return .= '
                        <a href="javascript:void(0)" ' . $closeScript . '>
                            <i class="fa fa-minus-circle pull-right fa-2x grey"></i>
                        </a>
                        ';
            }

            $return .= $content;
            $return .= '<div class="clear"></div></div>';
        }

        return $return;
    }
}
