[![CircleCI](https://circleci.com/gh/AlexanderGranhof/weather.svg?style=svg)](https://circleci.com/gh/AlexanderGranhof/weather)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AlexanderGranhof/weather/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AlexanderGranhof/weather/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/AlexanderGranhof/weather/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## Usage

Require the file via the namespace `algn/DarkSky` and createa new instance of the class by passing your api key in the constructor.

You can use the following methods to retrieve data from dark sky.

`today()` - Returns weather data from today
`week()` - Returns weather data from today and a week ahead
`past30Days()` - Returns data from the past month
