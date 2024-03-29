<?php
class ManifestMaker {
    private $base = [
        "name"=>"IAPP",
        "start_url"=>"/?v=1",
        "display"=>"standalone",
        "orientation"=>"portrait",
        "short_name"=>"iapp",
        "description"=>"App make app",
        "id"=>"v1",
        "background_color"=>"#999999",
        "theme_color"=>"#000000",
        "icons"=>[
                [
                "src"=>"/img/icon/android-icon-36x36.png",
                "sizes"=>"36x36",
                "type"=>"image/png",
                "density"=>"0.75"
                ],[
                "src"=>"/img/icon/android-icon-48x48.png",
                "sizes"=>"48x48",
                "type"=>"image/png",
                "density"=>"1.0"
                ],[
                "src"=>"/img/icon/android-icon-72x72.png",
                "sizes"=>"72x72",
                "type"=>"image/png",
                "density"=>"1.5"
                ],[
                "src"=>"/img/icon/android-icon-96x96.png",
                "sizes"=>"96x96",
                "type"=>"image/png",
                "density"=>"2.0"
                ],[
                "src"=>"/img/icon/android-icon-144x144.png",
                "sizes"=>"144x144",
                "type"=>"image/png",
                "density"=>"3.0"
                ],[
                "src"=>"/img/icon/android-icon-192x192.png",
                "sizes"=>"192x192",
                "type"=>"image/png",
                "density"=>"4.0"
                ],[
                "src"=>"/img/icon/apple-icon-57x57.png",
                "sizes"=>"57x57",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-60x60.png",
                "sizes"=>"60x60",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-76x76.png",
                "sizes"=>"76x76",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-114x114.png",
                "sizes"=>"114x114",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-120x120.png",
                "sizes"=>"120x120",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-144x144.png",
                "sizes"=>"144x144",
                "type"=>"image/png"
                ],[
                    "src"=>"/img/icon/apple-icon-144x144.png",
                    "sizes"=>"144x144",
                    "type"=>"image/png",
                    "purpose"=>"any"
                    ],[
                "src"=>"/img/icon/apple-icon-152x152.png",
                "sizes"=>"152x152",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-180x180.png",
                "sizes"=>"180x180",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-precomposed.png",
                "sizes"=>"192x192",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/apple-icon-precomposed.png",
                "sizes"=>"192x192",
                "type"=>"image/png",
                "purpose"=>"maskable"
                ],[
                "src"=>"/img/icon/maskable.png",
                "sizes"=>"512x512",
                "type"=>"image/png",
                "purpose"=>"any maskable"
                ],[
                "src"=>"/img/icon/icon-512x512.png",
                "type"=>"image/png",
                "sizes"=>"512x512",
                "purpose"=>"any"
                ],[
                "src"=>"/img/icon/favicon-16x16.png",
                "sizes"=>"16x16",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/favicon-32x32.png",
                "sizes"=>"32x32",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/favicon-96x96.png",
                "sizes"=>"96x96",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/ms-icon-70x70.png",
                "sizes"=>"70x70",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/ms-icon-144x144.png",
                "sizes"=>"144x144",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/ms-icon-150x150.png",
                "sizes"=>"150x150",
                "type"=>"image/png"
                ],[
                "src"=>"/img/icon/ms-icon-310x310.png",
                "sizes"=>"310x310",
                "type"=>"image/png"
            ],[
                "src"=>"/img/icon/ms-icon-310x310.png",
                "sizes"=>"310x310",
                "type"=>"image/png"
            ]
        ]
        ];

    public function __construct($i_app, $style) {
      
        $this->base["name"] = $i_app["name"];
        $this->base["short_name"] = $i_app["short_name"];
        $this->base["id"] = $i_app["id"];
        $this->base["start_url"] = "/?v=".$i_app["id"];
        $this->base["description"] = $i_app["description"];
        $themeColor =  $style["PR_D"];
        if(isset($style["theme"])){
            $themeColor =  $style["theme"];
        }
        $this->base["theme_color"] =  $themeColor;
        $this->base["background_color"] = $style["PR"];

    }
    public function __toString()
    {
        return (string) json_encode( $this->base);
    }
}
/*
// Example usage
$i_app = [...]; // Set your i_app array
$style = [...]; // Set your style array or pass null if not required

$manifestMaker = new ManifestMaker();
$result = $manifestMaker->manifestMaker($i_app, $style);
echo json_encode($result);*/