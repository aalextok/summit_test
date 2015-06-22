<?php
namespace frontend\widgets;

use \backend\models\Place;

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;

/**
 * Render google maps for place
 */
class PlaceMapRenderer extends \yii\bootstrap\Widget
{
    var $place = null;
    
    public function init()
    {
        parent::init();

        $coord = new LatLng(['lat' => $this->place->latitude, 'lng' => $this->place->longtitude]);
        $map = new Map([
            'center' => $coord,
            'zoom' => 14,
            'width' => '100%',
        ]);
        
        $marker = new Marker([
            'position' => $coord,
            'title' => $this->place->name,
        ]);
        
        $marker->attachInfoWindow(
            new InfoWindow([
                'content' => '<p>' . $this->place->description . '</p>'
                ])
        );
        
        $map->addOverlay($marker);
        
        echo $map->display();
        
    }
}
