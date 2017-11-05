<?php
/**
 * @license See the file LICENSE for copying permission.
 */

namespace Soflomo\Purifier\Factory;

use Soflomo\Purifier\PurifierFilter;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;

class PurifierFilterFactory implements MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    public function __invoke(FilterPluginManager $filterPluginManager)
    {
        $serviceLocator = $filterPluginManager->getServiceLocator();

        /** @var \HTMLPurifier $htmlPurifier */
        $htmlPurifier = $serviceLocator->get('HTMLPurifier');

        $config = $serviceLocator->get('config');
        if (!empty($config['soflomo_purifier'])) {
            $config = array_replace_recursive(
                $config['soflomo_purifier'],
                $this->getCreationOptions()
            );

            unset($config['standalone']);
            unset($config['standalone_path']);
        } else {
            $config = $this->getCreationOptions();
        }

        return new PurifierFilter($htmlPurifier, $config);
    }
}
