<?php

namespace Bladepack\Bladepack\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
  public string $text;
  public bool $secondary;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct(string $text = null, bool $secondary = null)
  {
    $this->text = $text ? __($text) : null;
    $this->secondary = !!$secondary;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    return view('bladepack::components.button');
  }
}
