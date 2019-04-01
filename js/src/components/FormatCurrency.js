import React from 'react';

function FormatCurrency(props) {
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    });

    return (
        <div>
            {formatter.format(props.value)}
        </div>
    );
}

export default FormatCurrency;
