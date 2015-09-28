# pEEnterest

pEEnterest is a plugin that will generate a list of pins from the given board id.

## Usage
Token can be generated here
https://developers.pinterest.com/docs/api/access_token/

````
{exp:peenterest token="ACCESS_TOKEN" board_id="BOARD_ID" images="yes|no" creator="yes|no" counts="yes|no" media="yes|no"}
	{id}
	{url}
	{link}
	{created_at}
	{note}
	{color}
	{creator}
		{id}
		{first_name}
		{last_name}
		{url}
	{/creator}
	{media}
		{type}
	{/media}
	{board}
		{id}
		{url}
		{name}
	{/board}
	{counts}
		{likes}
		{comments}
		{repins}
	{/counts}
	{image}
		{original} // or small|medium|large
			{url}
			{width}
			{height}
		{/original}
	{/image}
{/exp:peenterest}
````