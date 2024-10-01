<?php

class JsonDB
{
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    // Load the JSON file and decode it into an array
    private function loadJson()
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

    // Save the array back into the JSON file
    private function saveJson($data)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json);
    }

    // Get: Find and return the entries matching the array of conditions
    public function get(array $conditions)
    {
        $data = $this->loadJson();
        $results = array_filter($data, function ($item) use ($conditions) {
            foreach ($conditions as $condition) {
                $key = $condition['key'];
                $value = $condition['value'];
                $relation = $condition['relation'];

                if (!isset($item[$key])) {
                    return false;
                }

                // Check the relation condition
                switch ($relation) {
                    case 'eq':   // Equal
                        if ($item[$key] != $value) return false;
                        break;
                    case 'uneq': // Not equal
                        if ($item[$key] == $value) return false;
                        break;
                    case 'gr':   // Greater than
                        if ($item[$key] <= $value) return false;
                        break;
                    case 'le':   // Less than
                        if ($item[$key] >= $value) return false;
                        break;
                    default:
                        return false; // Invalid relation
                }
            }
            return true;
        });
        return $results;
    }

    // Insert: Add a new entry to the JSON file
    public function in($newItem)
    {
        $data = $this->loadJson();
        $data[] = $newItem;
        $this->saveJson($data);
        return true;
    }

    // Update: Update entries matching the key-value pair
    public function up($key, $value, $updatedData)
    {
        $data = $this->loadJson();
        foreach ($data as &$item) {
            if (isset($item[$key]) && $item[$key] == $value) {
                $item = array_merge($item, $updatedData); // Merge updated data
            }
        }
        $this->saveJson($data);
        return true;
    }

    // Delete: Remove entries matching the key-value pair
    public function del($key, $value)
    {
        $data = $this->loadJson();
        $data = array_filter($data, function ($item) use ($key, $value) {
            return !(isset($item[$key]) && $item[$key] == $value);
        });
        $this->saveJson($data);
        return true;
    }
}



?>
