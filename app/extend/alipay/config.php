<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016092300576637",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCGsJqi6fS3qdgXRatf2XQzVymwNPDyQPj5u/jpJbuSkkhWfEQUNnJiK8h5DLfnl/iaxD8MnK0BGnUWtj6s2ulSOZRRY5OAj/PRG5vTO2Y7Nwv5ZHrqqPoohxEqpsmpeWievzseyUZNuNEqCvmyybh2DUimzuMYBkE5duvRZUjy/yyOBowmDBm367t1HMzeR3OWP2C9/ORLLKGyDOlZwauYzwRR32XkcefpLm0Dcqx3tVCMgFU8l6Jlbx8U0IiagOLBa+lmjgGOr7TBffyw/CTBigrV5SEkzLElCWaEjZ2CEqNuevHF2bGUk2HnHH7PbObWzay0QQASixOLp0eXmRytAgMBAAECggEAKyNZA6Cm7kQJn7qCntpIq0DZa0Qpf41rsKCRoiqhntoXLQvzyf1+OQ8I0CxQom48A2WjQ+jSSC4vHi0UecxLOjiS1lZsIZr5XhnXmJnHrmN9BE04SPwB0wUfbGeq4wqthVp+FugkCjSNWUfWnXqaB8VNZY+RkFpAfaUm9hPYf2rerf3GNFvLF6lBJfZ0RtPHnypnyp1AkJjhkWLltpbPltiyWjxy/4NbFEIqctogQAFFI+LVTS2F4D33JpgYRmOlpOWdLlt5To3Ie4cs9+hKqiIWEHo6oPdaQ/uKdiBggSmjlFI5sALXGYPfr51Sug+2mwbdMDJcsjXfCJsAYnNMyQKBgQDWcUSF6bCtboC6Gf099AadHza7Ls6azsO/kCI0CIVSZ2Nm/uVZAf/9PYCs90FILNNhjTWppjpMPBzFJGQfUmLLfql6Vdh3idiCATRZZPlqUc8AWgLQFAnpauYlQktad/OyIqoyjc+NIgRNRTp9cRAnJaVyJX35Ug4FoPI/LsxbbwKBgQCgyrhczanmjIrHaKRAO7+G+4JtWKzSQcbXa79ZaZK4ZsNwBAhajQrTk1gZaNpxdoCX4JTuX8OTzeZ4bExTDd55geWCaK9ldECouNPjjuE0thHTyfYJaPCyRQ5FmtFqBkPdJfwkJwgUcQ8gfk5gms09O0Y6ni7z3XW+7YlI7zTrowKBgFkoXsUOlFaHSx4VECViAB3zjF3m6B+VN+29j3ryz/ui4MFB2TlYi4ZOu+EuLAZGHNzCST1vz21C0PNmXfpn1ouk7fHfH1EUDuB+f7VrNgfuW5JZ2jGJI1XZDDcRiSLkHH5Dy1+ZdmHHcuJtlhPxnjWogVEkJWwzoYDVmeKLIowhAoGAX3EVUBjP8caYldb6MBqHDGN8PtW097UNh08wrK0q5wQwm+v43W787yt1ztdbWqAhooUvQfNcDv7X/8Eg6OfBl9RP3EGxYwJHYx8avudPrE8qc9FclqdQwDCXUR+dkFaZr8pdSURV2nRlqz7t3Q/mJaNqg17jteNNggBTThSXEAsCgYADgbwvCrzaDhS/XWOOnptxJuLAKDVwmLaa23TtV/LvtLMj6p9rXBSeRX5fQ3z2CQLnDaBzmkPxiiEewrmuUK7WoVj4FHb4qaU3vuz7WYXEFo/2qjHWAbQB/8J9p9TKNSA16He8SaD+9z66EQYB9siSGYVsuvrtYgpffgDHgI6L7Q==",
		
		//异步通知地址
		'notify_url' => "http://120.78.80.237/tell",
		
		//同步跳转
		'return_url' => "http://120.78.80.237/result",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhrCaoun0t6nYF0WrX9l0M1cpsDTw8kD4+bv46SW7kpJIVnxEFDZyYivIeQy355f4msQ/DJytARp1FrY+rNrpUjmUUWOTgI/z0Rub0ztmOzcL+WR66qj6KIcRKqbJqXlonr87HslGTbjRKgr5ssm4dg1Ips7jGAZBOXbr0WVI8v8sjgaMJgwZt+u7dRzM3kdzlj9gvfzkSyyhsgzpWcGrmM8EUd9l5HHn6S5tA3Ksd7VQjIBVPJeiZW8fFNCImoDiwWvpZo4Bjq+0wX38sPwkwYoK1eUhJMyxJQlmhI2dghKjbnrxxdmxlJNh5xx+z2zm1s2stEEAEosTi6dHl5kcrQIDAQAB",
		
	
);
return $config;