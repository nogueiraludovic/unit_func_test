<?php

namespace Vd\VdOcosp\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View Helper for embedding movies.
 *
 * = Examples =
 *
 * <code title="Example">
 * <vd:zoomMovie url="https://www.youtube.com/watch?v=hp69rg6Hdlo">
 * </vd:zoomMovie>
 * </code>
 * <output>
 * https://www.youtube.com/embed/hp69rg6Hdlo
 * </output>
 */
class ZoomMovieViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * Grabs a video URL and returns an embed code.
     * case https://youtu.be/MaFPFtGs5Pc not a valid url
     * https://www.youtube.com/embed/MaFPFtGs5Pc
     * @return string Rendered string
     * @api
     */
    public function render()
    {
        $url = $this->arguments['url'];
        // use shortcut
        if (substr($url, 0, 16) === 'https://youtu.be') {
            $url = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $url);
        }
        if (substr($url, 0, 23) === 'https://www.youtube.com') {
            return '<div id="playerwIHzmxeeNDQH"><iframe style="width: 100%;height:400px" src="' . $url . '"></iframe></div>';
        }
        // www.zoomsurlesmetiers.ch
        $movieEmbed = '
			<div id="playerwIHzmxeeNDQH"></div>
			<script>
			  jwplayer("playerwIHzmxeeNDQH").setup({
			      file: "' . $url . '",
			      image: "https://www.zoomsurlesmetiers.ch/pages/thumbs/home.jpg",
			      width: "100%",
			      aspectratio: "16:9"
			  });
			</script>
		';
        return $movieEmbed;
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('url', 'string', 'URL of a movie', true);
    }
}
