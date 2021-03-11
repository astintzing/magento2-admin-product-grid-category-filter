<?php

namespace Utklasad\AdminProductGridCategoryFilter\Model\Category;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CategoryList implements ArrayInterface
{
    protected $_categoryCollectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->_categoryCollectionFactory = $collectionFactory;
    }
    
    public function toOptionArray($addEmpty = true)
    {
        $categoryCollection = $this->_categoryCollectionFactory->create()->addAttributeToSelect('name')->setOrder('name', 'ASC');

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Category --'), 'value' => ''];
        }

        foreach ($categoryCollection as $category) {
            $options[] = ['label' => $this->_getParentsPath($category), 'value' => $category->getId()];
        }

        return $options;
    }

    protected function _getParentsPath($category)
    {
        $path = '';
        $parentCategories = $category->getParentCategories();
        foreach($parentCategories as $pc) {
            $path .= $pc->getName() . ' » ';
        }

        if (empty($parentCategories)) {
            $path = $category->getName();
        } else {
            $path = substr($path, 0, -3);
        }

        return $path;
    }
}
