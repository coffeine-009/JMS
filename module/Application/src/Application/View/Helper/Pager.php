<?php
/// *** View :: Helper  *** *** *** *** *** *** *** *** *** *** *** *** *** ///

    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     *                                                              *
     * @copyright (c), 2013 by Vitaliy Tsutsman 
     * @author Vitaliy Tsutsman
     *
     * @date 2013-11-18 22:28:14 :: 2013::11:30 10:48:00
     *
     * @address /Ukraine/Ivano-Frankivsk/Chornovola/103/2
     *
     * @description Hepler for generation params 
     *  to display pages links template
     *                                                              *
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *

/// *** Code    *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ///
namespace Application\View\Helper
{
    //- Dependecies -//
    use \Zend\View\Helper\AbstractHelper;

    /** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Pages
     *  --- --- --- --- --- --- --- --- --- --- --- --- *
     * Methods:
     *  invoke
     * 
     * @example twig
     *  <code>
     *      {# Pager #}
     *      {% 
     *          include 
     *              "pager.twig" 
     *          with 
     *              pager( 
     *                  '/journal/list/creation/desc/%s/%s', 
     *                  pages.count, 
     *                  pages.active, 
     *                  pages.countDisplay, 
     *                  pages.countPagesLinks 
     *              ) 
     *      %}
     *  </code>
    *///*** *** *** *** *** *** *** *** *** *** *** *** *
    class Pager
        extends
            AbstractHelper
    {
        
        /// *** Methods     *** ///
        /** *** *** *** *** *** *** *** *** *** *
         * __invoke
         *  --- --- --- --- --- --- --- --- --- *
         * @access public
         * 
         * @param string    $LinkMask       Mask for build link
         * @param int       $Count          Count all items
         * @param int       $Active         Active page
         * @param int       $CountItems     Count items on page
         * @param int       $CountDisplay   Count page's links display
         * @param int       $PrevLabel      Label for prev button
         * @param int       $NextLabel      Label for next button
         * 
         * @return array Params for template
        *///*** *** *** *** *** *** *** *** *** *
        public function __invoke( 
            /*string*/  $LinkMask,          //- Mask for build link         -//
            /*int*/     $Count,             //- Count all items             -//
            /*int*/     $Active,            //- Active page                 -//
            /*int*/     $CountItems,        //- Count items on page         -//
            /*int*/     $CountDisplay = 9,  //- Count page's links display  -//
            /*string*/  $PrevLabel = '<',   //- Label for prev button       -//
            /*string*/  $NextLabel = '>'    //- Label for next button       -//
        )// : array
        {
            //- Generate response -//
            //- Prev and next buttons -//
            $result = array( 
                'prev'  => array( 
                    'label' => $PrevLabel, 
                    'active'=> ( $Active > 1 ), 
                    'url'   => sprintf( 
                        $LinkMask, 
                        $Active - 1, 
                        $CountItems
                    )
                ), 
                'next'  => array(
                    'label' => $NextLabel, 
                    'active'=> ( ($Active * $CountItems) < $Count ), 
                    'url'   => sprintf( 
                        $LinkMask, 
                        $Active + 1, 
                        $CountItems
                    )
                ), 
                'pages' => array()
            );
            
            //- Calculate left and right pages for display -//
            $leftPos = (int)($Active - ($CountDisplay / 2) + 1);
            $rightPos = (int)($Active + ($CountDisplay / 2));

            //- Correcting range( left an right positions ) -//
            //- When left pos out of range -//
            if( $leftPos < 1 )
            {
                $leftPos = 1;
            }
            
            //- When right pos out of range -//
            if( ($rightPos * $CountItems) >= $Count )
            {
                $rightPos = ($Count / $CountItems);
                
                //- if exist non full page -//
                if( !is_int( $rightPos) )
                {
                    $rightPos = (int)$rightPos + 1;
                }
            }

            //- Generation links for pages -//
            for( $i = $leftPos; $i <= $rightPos; $i++ )
            {
                $result[ 'pages' ][] = array(
                    'active'=> ( $i == $Active ), 
                    'label' => $i, 
                    'url'   => sprintf( 
                        $LinkMask, 
                        $i, 
                        $CountItems
                    )
                );
            }
            
            return $result;
        }
    }
}
