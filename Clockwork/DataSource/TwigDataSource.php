<?php namespace Clockwork\DataSource;

use Clockwork\Request\Request;
use Clockwork\Support\Twig\ProfilerClockworkDumper;

use Twig_Environment;
use Twig_Extension_Profiler;
use Twig_Profiler_Profile;

/**
 * Data source for Twig, provides views data
 */
class TwigDataSource extends DataSource
{
	protected $profile;

	/**
	 * Create a new data source, takes Twig instance as an argument
	 */
	public function __construct(Twig_Environment $twig)
	{
		$twig->addExtension(new Twig_Extension_Profiler($this->profile = new Twig_Profiler_Profile));
	}

	/**
	 * Adds email data to the request
	 */
	public function resolve(Request $request)
	{
		$timeline = (new ProfilerClockworkDumper)->dump($this->profile);

		$request->viewsData = array_merge($request->viewsData, $timeline->finalize());

		return $request;
	}
}
