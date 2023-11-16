function showFullTooltip(row, size, value) {
    // Se o mouse estiver sobre o Brasil (linha 0)
    if (row === 0) {
        // Obtém o valor da população do Brasil (posição 2 na tabela)
        var populacaoBrasil = data.getValue(0, 2);

        // Formata o valor usando pontos para milhares e vírgulas para decimais
        var formattedPopulacaoBrasil = populacaoBrasil.toLocaleString('pt-BR');

        return '<div style="background:#fd9; padding:10px; border-style:solid">' +
        '<span style="font-family:Courier"><b>' + data.getValue(row, 0) + '</b></span><br>' +
        'População da Cidade: ' + formattedPopulacaoCidade + '<br>' +
        'População do Brasil: ' + formattedPopulacaoBrasil + '<br>' +
        'Porcentagem da População da Cidade: ' + formattedPorcentagem + '%' +
        '</div>';
    }

    // Se o mouse estiver sobre uma cidade (outra linha)
    else {
        // Obtém o valor da população da cidade
        var populacaoCidade = data.getValue(row, 3);

        // Formata o valor usando pontos para milhares e vírgulas para decimais
        var formattedPopulacaoCidade = populacaoCidade.toLocaleString('pt-BR');

        return '<div style="background:#fd9; padding:10px; border-style:solid">' +
            '<span style="font-family:Courier"><b>' + data.getValue(row, 0) + '</b></span><br>' +
            'População da Cidade: ' + formattedPopulacaoCidade +
            '</div>';
    }
}
