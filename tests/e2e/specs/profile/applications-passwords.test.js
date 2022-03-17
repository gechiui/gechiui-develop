import {
	visitAdminPage,
	__experimentalRest as rest,
} from "@gechiui/e2e-test-utils";

async function getResponseForApplicationPassword() {
	return await rest({
		method: "GET",
		path: "/gc/v2/users/me/appkeys",
	});
}

async function createApplicationPassword(applicationName) {
	await visitAdminPage("profile.php");
	await page.waitForSelector("#new_appkey_name");
	await page.type("#new_appkey_name", applicationName);
	await page.click("#do_new_appkey");

	await page.waitForSelector("#appkeys-section .notice");
}

async function createApplicationPasswordWithApi(applicationName) {
	await rest({
		method: "POST",
		path: "/gc/v2/users/me/appkeys",
		data: {
			name: applicationName,
		},
	});
}

async function revokeAllApplicationPasswordsWithApi() {
	await rest({
		method: "DELETE",
		path: `/gc/v2/users/me/appkeys`,
	});
}

describe("Manage applications passwords", () => {
	const TEST_APPLICATION_NAME = "Test Application";

	beforeEach(async () => {
		await revokeAllApplicationPasswordsWithApi();
	});

	it("should correctly create a new appkey", async () => {
		await createApplicationPassword(TEST_APPLICATION_NAME);

		const response = await getResponseForApplicationPassword();
		expect(response[0]["name"]).toBe(TEST_APPLICATION_NAME);

		const successMessage = await page.waitForSelector(
			"#appkeys-section .notice-success"
		);
		expect(
			await successMessage.evaluate((element) => element.innerText)
		).toContain(
			`Your new password for ${TEST_APPLICATION_NAME} is: \n\nBe sure to save this in a safe location. You will not be able to retrieve it.`
		);
	});

	it("should not allow to create two applications passwords with the same name", async () => {
		await createApplicationPassword(TEST_APPLICATION_NAME);
		await createApplicationPassword(TEST_APPLICATION_NAME);

		const errorMessage = await page.waitForSelector(
			"#appkeys-section .notice-error"
		);

		expect(
			await errorMessage.evaluate((element) => element.textContent)
		).toContain("Each application name should be unique.");
	});

	it("should correctly revoke a single appkey", async () => {
		await createApplicationPassword(TEST_APPLICATION_NAME);

		const revokeApplicationButton = await page.waitForSelector(
			".appkeys-user tr button.delete"
		);
		
		const revocationDialogPromise = new Promise((resolve) => {
			page.once("dialog", resolve);
		});

		await Promise.all([
			revocationDialogPromise,
			revokeApplicationButton.click(),
		]);

		const successMessage = await page.waitForSelector(
			"#appkeys-section .notice-success"
		);
		expect(
			await successMessage.evaluate((element) => element.textContent)
		).toContain("Appkey revoked.");

		const response = await getResponseForApplicationPassword();
		expect(response).toEqual([]);
	});

	it("should correctly revoke all the appkeys", async () => {
		await createApplicationPassword(TEST_APPLICATION_NAME);

		const revokeAllApplicationPasswordsButton = await page.waitForSelector(
			"#revoke-all-appkeys"
		);

		const revocationDialogPromise = new Promise((resolve) => {
			page.once("dialog", resolve);
		});

		await Promise.all([
			revocationDialogPromise,
			revokeAllApplicationPasswordsButton.click(),
		]);

		/**
		 * This is commented out because we're using enablePageDialogAccept
		 * which is overly aggressive and no way to temporary disable it either.
		 */
		// await dialog.accept();

		await page.waitForSelector(
			"#appkeys-section .notice-success"
		);

		const successMessage = await page.waitForSelector(
			"#appkeys-section .notice-success"
		);
		expect(
			await successMessage.evaluate((element) => element.textContent)
		).toContain("All appkeys revoked.");

		const response = await getResponseForApplicationPassword();
		expect(response).toEqual([]);
	});
});
