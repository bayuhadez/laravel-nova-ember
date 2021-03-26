<?php

namespace App\Observers;

use App\Models\Faq;

class FaqObserver
{
	/**
	 * Handle the faq "created" event.
	 *
	 * @param  \App\Faq  $faq
	 * @return void
	 */
	public function creating(Faq $faq)
	{
		$user = auth()->user();

		// auto set user_id
		if (!empty($user)) {

			// set user_id as author
			if (empty($faq->created_by)) {
				$faq->created_by = $user->id;
			}

			// set company_id
			if (
				empty($faq->company_id) &&
				!is_null($user->getCurrentCompanyId())
			) {
				$faq->company_id = $user->getCurrentCompanyId();
			}
		}
	}

}
