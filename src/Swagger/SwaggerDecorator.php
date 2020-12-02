<?php

namespace App\Swagger;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    /**
     * Service to normalize
     *
     * @var NormalizerInterface
     */
    protected $decorated;

    /**
     * Construction function
     *
     * @param NormalizerInterface $decorated Service NormalizerInterface
     */
    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * Check if this class supports the sent data
     *
     * @param mixed  $data   The data to normalize
     * @param string $format The normalization format
     *
     * @return boolean
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    /**
     * Normalize the data
     *
     * @param mixed  $object  The data to normalize
     * @param string $format  The format to normalize
     * @param array  $context Settings
     *
     * @return void
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        unset($docs["paths"]["/users/{id}"]);

        $docs['components']['securitySchemes']['jwt']['description']
            .= ": Bearer MY_NEW_TOKEN";

        $docs['components']['schemas']['Token'] = [
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ];

        $docs['components']['schemas']['Credentials'] = [
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'demo@domain.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'demo',
                ],
            ],
        ];

        $docs['components']['schemas']['Balance'] = [
            'type' => 'object',
            'properties' => [
                'total' => [
                    'type' => 'number'
                ]
            ],
        ];

        $tokenDocumentation = [
            'paths' => [
                '/login' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'operationId' => 'postCredentialsItem',
                        'summary' => 'Get JWT token to login.',
                        'requestBody' => [
                            'description' => 'Create new JWT Token',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/Credentials',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get JWT token',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/Token',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/balance' => [
                    'get' => [
                        'tags' => ['User'],
                        'operationId' => 'getBalanceUser',
                        'summary' => 'Get User Balance.',
                        'requestBody' => [
                            'description' => 'Get User Balance'
                        ],
                        'responses' => [
                            Response::HTTP_OK => [
                                'description' => 'Get User Balance',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/Balance',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ],
        ];

        return array_merge_recursive($docs, $tokenDocumentation);
    }
}
