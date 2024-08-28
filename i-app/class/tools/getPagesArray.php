<?php 
class getPagesArray{
    public $getArray = [];
    function __construct($treeA, $pathDA)
    {
        $this->getArray = $this->getDirectoryArray_($treeA, $pathDA); 
    }
   

function getDirectoryArray_($tree, $pathD)
{
    $ar = [];
    $lastPath = '';



    if ($tree['type'] == "folder") {

        if ($tree['name'] !== '' && $tree['name'] !== 'public_html') {
            $lastPath = $pathD . '/' . $tree['name'];
        }

        foreach ($tree['children'] as $child) {

            if ($child['type'] == "folder") {

                $children = $this->getDirectoryArray_($child, $lastPath);
                foreach ($children as $childpath) {
                    array_push($ar, $childpath);
                }
              

            } else {

               
                if(isset($child["page"] ) && $child["page"] == true && isset($child["SEO"] ) && $child["SEO"] == true && $child["ext"] == "app"){
                 
                    $newPath =   $this->removeAppExtension($child['name']);
                    
                    array_push($ar,  $newPath);
                }
            }
        }
    } else {
        // file case
        if(isset($tree["page"] ) && $tree["page"] == true && isset($tree["SEO"] ) && $tree["SEO"] == true && $tree["ext"] == "app"){
            $newPath = $this->removeAppExtension($tree['name']);
            array_push($ar,  $newPath);
        }
      
    }

    return $ar;
}
function removeAppExtension($string) {
    // Check if the string ends with '.app'
    if (substr($string, -4) === '.app') {
        // Remove the '.app' extension
        return substr($string, 0, -4);
    }
    // If '.app' is not at the end, return the original string
    return $string;
}
function __toString()

{
    $data = json_encode($this->getArray );
    return  (string) $data;
}
}