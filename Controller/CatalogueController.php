<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogueController extends Controller
{
    protected static $count = 0;
    protected static $items = array();
    
    /**
     * @Route("/", name="dzangocart_catalogue")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $catalogue = $this->get('dzangocart')
            ->getCatalogue();

        $this->getTree($catalogue);

        return array(
            'catalogue' => self::$items
        );
    }

    protected function getTree($catalogue, $parent_id = 0 )
    {
        foreach ($catalogue as $item) {
            self::$count += 1;

            $item['parent_id'] = $parent_id;

            self::$items[self::$count] = array(
                'parent_id' => $item['parent_id'],
                'name' => $item['name'],
                'code' => $item['code'],
                'pcode' => $item['pcode'],
                'price' => $item['price'],
                'tax_included' => $item['taxIncluded']
            );

            if (array_key_exists('categories', $item)) {
                self::getTree($item['categories'], self::$count);
            }
        }
    }
}
