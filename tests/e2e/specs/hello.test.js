import { visitAdminPage } from '@gechiui/e2e-test-utils';

describe( 'Hello World', () => {
	it( 'Should load properly', async () => {
		await visitAdminPage( '/' );
		const nodes = await page.$x(
			'//h2[contains(text(), "Welcome to GeChiUI!")]'
		);
		expect( nodes.length ).not.toEqual( 0 );
	} );
} );
