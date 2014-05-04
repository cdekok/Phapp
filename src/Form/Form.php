<?php
namespace Phapp\Form;

class Form extends \Phalcon\Forms\Form {
    
    /**
     * Render form
     * @param string $method
     * @param string $action
     */
    public function render($method = 'post', $action = '') {
        $output = '<form method="'.$method.'" action="'.$action.'">';
        foreach ($this as $element) {
            $output .= $this->renderElement($element);
        }  
        $output .= '</form>';
        return $output;
    }
    
    /**
     * Render form element
     * @param \Phalcon\Forms\ElementInterface $element
     * @return string
     */
    protected function renderElement(\Phalcon\Forms\ElementInterface $element) {               
        $messages = $this->getMessagesFor($element->getName());        
        $output = '';
        if (count($messages)) {
            //Print each element
            $output .= '<div class="messages">';
            foreach ($messages as $message) {
                $output .= $this->flash->error($message);
            }
            $output .= '</div>';
        }
        $output .= '<p>';
        $output .= '<label for="'.$element->getName().'">'.$element->getLabel(). '</label>';
        $output .= $element;
        $output .= '</p>';
        return $output;
    }
}