<?php

namespace App\Core;

use App\Application;

class Components
{

    /** Component View System renderer: Rendering a view: layout (with static components) and content (with dynamic components)
     * @param string $layout
     * @param string $content
     * @param array $params optional
     */
    public function render($layout, $content, $params = [])
    {
        $layout = $this->layout($layout);
        $content = $this->content($content, $params);
        return str_replace('(( content ))', $content, $layout);
    }

    /** Layout where the content will be rendered
     * @param string $layout
     */
    protected function layout($layout)
    {
        // Get the requested content
        ob_start();
        include_once Application::$ROOT_DIR . "/app/views/layouts/$layout.php";
        $file = ob_get_clean();
        // Store the component names found into an array
        $static_names = $this->getComponentsNames($file, "[[ ", " ]]");
        // Placeholders array
        $static_placeholders = [];
        // Populate the placeholders array based on the components names array
        foreach ($static_names as $static_name) {
            array_push($static_placeholders, '[[ ' . $static_name . ' ]]');
        }
        // Static components
        $static_components = [];
        // Include components content into the components array
        foreach ($static_names as $static_name) {
            array_push($static_components, $this->component('static', $static_name));
        }
        // Replace the placeholders with their respective components
        return str_replace($static_placeholders, $static_components, $file);
    }

    /** Content/View requested by a web route
     * @param string $content
     * @param array $params optional
     */
    protected function content($content, $params = [])
    {
        // Get the parameters if defined
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        // Get the requested content
        ob_start();
        include_once Application::$ROOT_DIR . "/app/views/$content.php";
        $file = ob_get_clean();
        // Store the component names found into an array
        $dynamic_names = $this->getComponentsNames($file, "{{ ", " }}");
        // Placeholders array
        $dynamic_placeholders = [];
        // Populate the placeholders array based on the components names array
        foreach ($dynamic_names as $dynamic_name) {
            array_push($dynamic_placeholders, '{{ ' . $dynamic_name . ' }}');
        }
        // Dynamic components
        $dynamic_components = [];
        // Include components content into the components array
        foreach ($dynamic_names as $dynamic_name) {
            array_push($dynamic_components, $this->component('dynamic', $dynamic_name));
        }
        // Replace the placeholders with their respective components
        return str_replace($dynamic_placeholders, $dynamic_components, $file);
    }

    // Get unique component names
    public function getComponentsNames($file, $before_tag, $after_tag)
    {
        $result = [];
        foreach (explode($before_tag, $file) as $key => $value) {
            if (strpos($value, $after_tag) !== FALSE) {
                array_push($result, substr($value, 0, strpos($value, $after_tag)));
            }
        }
        return array_unique($result);
    }
    // Include component content
    public function component($type, $name)
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/app/views/components/$type/" . $name . ".php";
        return ob_get_clean();
    }
}
