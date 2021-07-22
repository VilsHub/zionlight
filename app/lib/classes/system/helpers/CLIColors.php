<?php
	class CLIColors {
		private $fontColors = [];
		private $backgroundColor = [];

		public function __construct() {
			// Set up shell colors
			$this->fontColors['black'] = '0;30';
			$this->fontColors['black_bold'] = '1;30';
			$this->fontColors['dark_gray'] = '1;30';
			$this->fontColors['blue'] = '0;34';
			$this->fontColors['light_blue'] = '1;34';
			$this->fontColors['green'] = '0;32';
			$this->fontColors['light_green'] = '1;32';
			$this->fontColors['cyan'] = '0;36';
			$this->fontColors['light_cyan'] = '1;36';
			$this->fontColors['red'] = '0;31';
			$this->fontColors['light_red'] = '1;31';
			$this->fontColors['purple'] = '0;35';
			$this->fontColors['light_purple'] = '1;35';
			$this->fontColors['brown'] = '0;33';
			$this->fontColors['yellow'] = '1;33';
			$this->fontColors['light_gray'] = '0;37';
			$this->fontColors['white'] = '1;37';

			$this->backgroundColor['black'] = '40';
			$this->backgroundColor['red'] = '41';
			$this->backgroundColor['green'] = '42';
			$this->backgroundColor['yellow'] = '43';
			$this->backgroundColor['blue'] = '44';
			$this->backgroundColor['magenta'] = '45';
			$this->backgroundColor['cyan'] = '46';
			$this->backgroundColor['light_gray'] = '47';
		}

		// Build and display colored text
		public function color($text, $fontColor, $backgroundColor) {
            /**
             * @param string $text The text to be colored
             * @param string $fontColor The desired font color
             * @param string $backgroundColor The font background
             */
			$coloredText = "";

			// Check if given foreground color found
			if (isset($this->fontColors[$fontColor])) {
				$coloredText .= "\033[" . $this->fontColors[$fontColor] . "m";
			}
			// Check if given background color found
			if (isset($this->backgroundColor[$backgroundColor])) {
				$coloredText .= "\033[" . $this->backgroundColor[$backgroundColor] . "m";
			}

			// Add string and end coloring
			return $coloredText .=  $text . "\033[0m";
		}

        public function write($text, $fontColor, $backgroundColor){
            /**
             * @param string $text The text to be colored
             * @param string $fontColor The desired font color
             * @param string $backgroundColor The font background
             */
            echo $this->color($text, $fontColor, $backgroundColor);
        }

		// Returns all foreground color names
		public function getFontColors() {
			return array_keys($this->fontColors);
		}

		// Returns all background color names
		public function getBackgroundColors() {
			return array_keys($this->backgroundColor);
		}
	}

?>