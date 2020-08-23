<?php

return [
    'user'         => [
        'insertion_failure' => 'Falha ao inserir usuário, tente novamente mais tarde',
        'transfer'          => [
            'invalid_type' => 'Usuários do tipo Lojista não podem realizar transferências'
        ]
    ],
    'wallet'       => [
        'credit_update_failure' => 'Falha ao adicionar créditos a carteira do usuário, tente novamente mais tarde',
        'limit_exceeded'        => 'Limite máximo da carteira foi excedido. Operação não realizada.',
        'not_found'             => 'Nenhuma carteira encontrada para o usuário informado',
        'transfer'              => [
            'without_credits'  => 'Usuário pagante não tem crédito suficiente para realizar essa transferência',
            'exceeded_credits' => 'Usuário beneficiado excedeu o limite de créditos'
        ]
    ],
    'transaction'  => [
        'payer_in_transaction' => 'Usuário pagante já está em uma transação, aguarde a finalização da mesma',
        'payee_in_transaction' => 'Usuário beneficiado já está em uma transação, aguarde a finalização da mesma',
        'error'                => 'Não foi possível realizar a transferência, tente novamente mais tarde'
    ],
    'integrations' => [
        'authorization_transactions_unavailable' => 'Serviço de autorização de transações está indisponível',
        'transaction_denied'                     => 'Transação negada'
    ]
];
