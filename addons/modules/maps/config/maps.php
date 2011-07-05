<?php

if (ENVIRONMENT == 'local')
{
	$config['maps_api_key'] = 'ABQIAAAAnhg5t4pKB12-7JCPjgR1cBTtRlB2QBdQnXfI7k0Y0WyO716NbRQ-mWJr1qQfZzlWKZs6cJOHUW3NAw';
}
elseif (ENVIRONMENT == 'production')
{
	if (strpos($base_url, '//www.') === false)
	{
		$config['maps_api_key'] = 'ABQIAAAAnhg5t4pKB12-7JCPjgR1cBRTI0NoYJO38Il7Lwg59T5Kh3kZtRSP49Kc94BbvTOEjHgrODPIXgsYdw';
	}
	else
	{
		$config['maps_api_key'] = 'ABQIAAAAnhg5t4pKB12-7JCPjgR1cBQ6-9eY1WRz7HznM4gTniZcZDFMnxQ9gjP_z7QIDPQ2f1oxEXJxub07bA';
	}
}
else
{
	$config['maps_api_key'] = 'ABQIAAAAnhg5t4pKB12-7JCPjgR1cBSCkH13aPu0dGdfCvacJ2GnLWClbxQnigt4v6tH7pud7Z12Ps_51ikLYA';
}

