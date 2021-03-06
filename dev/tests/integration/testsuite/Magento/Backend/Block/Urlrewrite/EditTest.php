<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Adminhtml
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Backend\Block\Urlrewrite;

/**
 * Test for \Magento\Backend\Block\Urlrewrite\Edit
 * @magentoAppArea adminhtml
 */
class EditTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test prepare layout
     *
     * @dataProvider prepareLayoutDataProvider
     *
     * @param array $blockAttributes
     * @param array $expected
     */
    public function testPrepareLayout($blockAttributes, $expected)
    {

        /** @var $layout \Magento\View\LayoutInterface */
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Core\Model\Layout',
            array('area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE)
        );

        /** @var $block \Magento\Backend\Block\Urlrewrite\Edit */
        $block = $layout->createBlock(
            'Magento\Backend\Block\Urlrewrite\Edit', '', array('data' => $blockAttributes)
        );

        $this->_checkSelector($block, $expected);
        $this->_checkButtons($block, $expected);
        $this->_checkForm($block, $expected);
    }

    /**
     * Check entity selector
     *
     * @param \Magento\Backend\Block\Urlrewrite\Edit $block
     * @param array $expected
     */
    private function _checkSelector($block, $expected)
    {
        $layout = $block->getLayout();

        /** @var $selectorBlock \Magento\Backend\Block\Urlrewrite\Selector|bool */
        $selectorBlock = $layout->getChildBlock($block->getNameInLayout(), 'selector');

        if ($expected['selector']) {
            $this->assertInstanceOf('Magento\Backend\Block\Urlrewrite\Selector', $selectorBlock,
                'Child block with entity selector is invalid');
        } else {
            $this->assertFalse($selectorBlock, 'Child block with entity selector should not present in block');
        }
    }

    /**
     * Check form
     *
     * @param \Magento\Backend\Block\Urlrewrite\Edit $block
     * @param array $expected
     */
    private function _checkForm($block, $expected)
    {
        $layout = $block->getLayout();
        $blockName = $block->getNameInLayout();

        /** @var $formBlock \Magento\Backend\Block\Urlrewrite\Edit\Form|bool */
        $formBlock = $layout->getChildBlock($blockName, 'form');

        if ($expected['form']) {
            $this->assertInstanceOf('Magento\Backend\Block\Urlrewrite\Edit\Form', $formBlock,
                'Child block with form is invalid');

            $this->assertSame($expected['form']['url_rewrite'], $formBlock->getUrlRewrite(),
                'Form block should have same URL rewrite attribute');
        } else {
            $this->assertFalse($formBlock, 'Child block with form should not present in block');
        }
    }

    /**
     * Check buttons
     *
     * @param \Magento\Backend\Block\Urlrewrite\Edit $block
     * @param array $expected
     */
    private function _checkButtons($block, $expected)
    {
        $buttonsHtml = $block->getButtonsHtml();

        if ($expected['back_button']) {
            $this->assertSelectCount('button.back', 1, $buttonsHtml,
                'Back button is not present in block');
        } else {
            $this->assertSelectCount('button.back', 0, $buttonsHtml,
                'Back button should not present in block');
        }

        if ($expected['save_button']) {
            $this->assertSelectCount('button.save', 1, $buttonsHtml,
                'Save button is not present in block');
        } else {
            $this->assertSelectCount('button.save', 0, $buttonsHtml,
                'Save button should not present in block');
        }

        if ($expected['reset_button']) {
            $this->assertSelectCount('button[title="Reset"]', 1, $buttonsHtml,
                'Reset button is not present in block');
        } else {
            $this->assertSelectCount('button[title="Reset"]', 0, $buttonsHtml,
                'Reset button should not present in block');
        }

        if ($expected['delete_button']) {
            $this->assertSelectCount('button.delete', 1, $buttonsHtml,
                'Delete button is not present in block');
        } else {
            $this->assertSelectCount('button.delete', 0, $buttonsHtml,
                'Delete button should not present in block');
        }
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function prepareLayoutDataProvider()
    {
        /** @var $urlRewrite \Magento\Core\Model\Url\Rewrite */
        $urlRewrite = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Core\Model\Url\Rewrite');
        /** @var $existingUrlRewrite \Magento\Core\Model\Url\Rewrite */
        $existingUrlRewrite = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Core\Model\Url\Rewrite',
            array('data' => array('url_rewrite_id' => 1))
        );

        return array(
            // Creating new URL rewrite
            array(
                array(
                    'url_rewrite' => $urlRewrite
                ),
                array(
                    'selector' => true,
                    'back_button' => true,
                    'save_button' => true,
                    'reset_button' => false,
                    'delete_button' => false,
                    'form' => array(
                        'url_rewrite' => $urlRewrite
                    )
                )
            ),
            // Editing URL rewrite
            array(
                array(
                    'url_rewrite' => $existingUrlRewrite
                ),
                array(
                    'selector' => true,
                    'back_button' => true,
                    'save_button' => true,
                    'reset_button' => true,
                    'delete_button' => true,
                    'form' => array(
                        'url_rewrite' => $existingUrlRewrite
                    )
                )
            )
        );
    }
}
